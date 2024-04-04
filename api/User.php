<?php
error_reporting(E_ALL);
class User {
    private $conn;
    private string $token;

    public function __construct() {
        require_once 'db.php';
        $database = new Database();
        $this->conn = $database->getDatabaseConnection();
    }

    private function generateToken(): string { 
        return bin2hex(random_bytes(32));
    }

    public function getToken(): string {
        return $this->token;
    }

    public function setToken(string $token): void {
        $this->token = $token;
    }
    
    public function endBet($betId, $result, $token) {
        // Ustawienie tokena użytkownika
        $this->setToken($token);
    
        // Sprawdzenie czy użytkownik jest administratorem
        $isAdmin = $this->isAdmin();
    
        if ($isAdmin) {
            // Jeśli użytkownik jest administratorem, wykonaj zakończenie zakładu
            $sqlUpdate = "UPDATE bets SET result = ? WHERE bet_id = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("si", $result, $betId);
            
            if ($stmtUpdate->execute()) {
                return ["success" => true, "message" => "Zakład zakończony pomyślnie"];
            } else {
                return ["success" => false, "message" => "Błąd podczas aktualizacji zakładu"];
            }
        } else {
            return ["success" => false, "message" => "Brak autoryzacji lub użytkownik nie jest administratorem"];
        }
    }
    
    private function saveTokenToDatabase(int $user_id, string $token, string $expiration_date): void {
        $sql = "INSERT INTO user_tokens (user_id, token, expiration_date) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $token, $expiration_date);
        $stmt->execute();
    }
    
    public function getBalance(): float | null {
        if (!$this->isLoggedIn()) {
            return null;
        }
    
        $token = $this->token; 
    
        $sql = "SELECT balance FROM users INNER JOIN user_tokens ON users.user_id = user_tokens.user_id WHERE token = ? AND expiration_date > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['balance'];
        } else {
            return null;
        }
    }
    
    public function getUserIdFromToken(string $token): string | null {
        $sql = "SELECT user_id FROM user_tokens WHERE token = ? AND expiration_date > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['user_id'];
        } else {
            return null;
        }
    }
    

    public function updateProfile(int $balance): bool {
        if (!$this->isLoggedIn()) {
            return false;
        }
    
        $user_id = $this->getUserIdFromToken($this->token);
    
        $balance = htmlspecialchars($balance, ENT_QUOTES);
    
        $sql = "UPDATE users SET balance = balance + ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("di", $balance, $user_id);
    
        return $stmt->execute();
    }
    
    public function updatePaidOutStatus(int $betId, bool $paidOutStatus): bool {
        $sql = "UPDATE user_bets SET paid_out = ? WHERE bet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $paidOutStatus, $betId);
        
        return $stmt->execute();
    }

    
    public function isBetEnded($betId) {
        $sql = "SELECT * FROM bets WHERE bet_id = ? AND result IS NOT NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $betId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    public function updateUserBalance($userId, $amount) {
        // Pobierz aktualny stan konta użytkownika
        $sql = "SELECT balance FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $currentBalance = $row['balance'];

        // Zaktualizuj stan konta o nową kwotę
        $newBalance = $currentBalance + $amount;

        // Zapisz nowy stan konta do bazy danych
        $sql = "UPDATE users SET balance = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("di", $newBalance, $userId);
        $stmt->execute();

        // Sprawdź, czy aktualizacja się powiodła
        if ($stmt->affected_rows > 0) {
            return true; // Zwróć true jeśli aktualizacja się powiodła
        } else {
            return false; // Zwróć false jeśli aktualizacja nie powiodła się
        }
    }

    public function placeBet(int $betId, string $selectedResult, int $betAmount, string $eventName, string $eventName2): bool {
        if (!$this->isLoggedIn()) {
            return false;
        }
    
        $user_id = $this->getUserIdFromToken($this->token);
    
        // Pobierz kursy z bets
        $sql = "SELECT team1_odds, team2_odds, draw_odds FROM bets WHERE bet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $betId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $team1_odds = $row['team1_odds'];
        $team2_odds = $row['team2_odds'];
        $draw_odds = $row['draw_odds'];
    
        $sqlCheck = "SELECT * FROM user_bets WHERE user_id = ? AND bet_id = ?";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $user_id, $betId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
    
        if ($resultCheck->num_rows > 0) {
            return false;
        }
        $eventName = htmlspecialchars($eventName, ENT_QUOTES);
        $eventName2 = htmlspecialchars($eventName2, ENT_QUOTES);
        $selectedResult = htmlspecialchars($selectedResult, ENT_QUOTES);

        $sql = "INSERT INTO user_bets (user_id, bet_id, event_name, event_name2, result, bet_amount, odds) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        
        // Ustawienie kursu z bets do user_bets w zależności od wybranego wyniku
        $odds = 0;
        if ($selectedResult == "Druzyna 1") {
            $odds = $team1_odds;
        } elseif ($selectedResult == "Druzyna 2") {
            $odds = $team2_odds;
        } elseif ($selectedResult == "Remis") {
            $odds = $draw_odds;
        }
        
        $stmt->bind_param("iisssid", $user_id, $betId, $eventName, $eventName2, $selectedResult, $betAmount, $odds);
    
        if ($stmt->execute()) {
            $this->updateOdds($betId, $selectedResult);
            return true;
        }
    
        return false;
    }
    
    //funckja ktory umozliwia postawienie wiecej niz 1 zakladu na uzytkownika
    // public function placeBet(int $betId, string $selectedResult, int $betAmount, string $eventName, string $eventName2): bool {
    //     if (!$this->isLoggedIn()) {
    //         return false;
    //     }
    
    //     // Pobierz identyfikator użytkownika
    //     $user_id = $this->getUserIdFromToken($this->token);
    
    //     // Dodaj nowy zakład
    //     $sql = "INSERT INTO user_bets (user_id, bet_id, event_name, event_name2, result, bet_amount) VALUES (?, ?, ?, ?, ?, ?)";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("iisssi", $user_id, $betId, $eventName, $eventName2, $selectedResult, $betAmount);
    
    //     if ($stmt->execute()) {
    //         return true;
    //     }
              
    //     return false;
    // }
    

    public function getAllBets(): array {
        $sql = "SELECT *, team1_odds AS team1, team2_odds AS team2 FROM bets"; // Dodaj aliasy dla kolumn team1_odds i team2_odds
        $result = $this->conn->query($sql);
    
        $bets = array();
        while ($row = $result->fetch_assoc()) {
            $bets[] = $row;
        }
    
        return $bets;
    }
      
    public function getBetResult($betId) {
        $sql = "SELECT result FROM bets WHERE bet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $betId);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();

        return $result;
    }
    
    public function getUserBets(): array {
        if (!$this->isLoggedIn()) {
            return array();
        }
    
        $user_id = $this->getUserIdFromToken($this->token);    

        $sql = "SELECT * FROM user_bets WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $user_bets = array();
        while ($row = $result->fetch_assoc()) {
            $user_bets[] = $row;
        }
    
        return $user_bets;
    }
    
    public function isLoggedIn(): bool {
        if (isset($this->token)) {
            $token = $this->token;
            $sql = "SELECT * FROM user_tokens WHERE token = ? AND expiration_date > NOW()";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return true;
            }
        }
        return false;
    }
    
    // public function updateOdds(int $betId, string $selectedResult) {
    //     $sql = "SELECT team1_odds, team2_odds FROM bets WHERE bet_id = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $betId);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $row = $result->fetch_assoc();
    //     $team1_odds = $row['team1_odds'];
    //     $team2_odds = $row['team2_odds'];
    
    //     $sql = "SELECT COUNT(*) as total FROM user_bets WHERE bet_id = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $betId);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $row = $result->fetch_assoc();
    //     $totalBets = $row['total'];
    
    //     // Ustalanie procentowego zmniejszenia/zwiększenia kursów
    //     $percentageChange = 0.05; // Zakładamy 5% zmiany kursu
    
    //     // Aktualizacja kursów w zależności od wyniku
    //     if ($selectedResult == "win") {
    //         // Oblicz nowe kursy dla drużyny 1 i drużyny 2
    //         $newOdds1 = $team1_odds * (1 - $percentageChange);
    //         $newOdds2 = $team2_odds * (1 + $percentageChange);
    //         $updateSql = "UPDATE bets SET team1_odds = ?, team2_odds = ? WHERE bet_id = ?";
    //     } elseif ($selectedResult == "loss") {
    //         // Oblicz nowe kursy dla drużyny 1 i drużyny 2
    //         $newOdds1 = $team1_odds * (1 + $percentageChange);
    //         $newOdds2 = $team2_odds * (1 - $percentageChange);
    //         $updateSql = "UPDATE bets SET team1_odds = ?, team2_odds = ? WHERE bet_id = ?";
    //     }
    
    //     // Zapisz nowe kursy do bazy danych
    //     if (!empty($updateSql)) {
    //         $stmt = $this->conn->prepare($updateSql);
    //         $stmt->bind_param("ddi", $newOdds1, $newOdds2, $betId);
    //         $stmt->execute();
    //     }
    // }
    
    public function updateOdds(int $betId, string $selectedResult) {
        $sql = "SELECT team1_odds, team2_odds, draw_odds FROM bets WHERE bet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $betId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $team1_odds = $row['team1_odds'];
        $team2_odds = $row['team2_odds'];
        $draw_odds = $row['draw_odds']; // Pobierz kurs dla remisu
    
        $sql = "SELECT COUNT(*) as total FROM user_bets WHERE bet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $betId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $totalBets = $row['total'];
    
        // Ustalanie procentowego zmniejszenia/zwiększenia kursów
        $percentageChange = 0.05; // Zakładamy 5% zmiany kursu
    
        // Aktualizacja kursów w zależności od wyniku
        if ($selectedResult == "Druzyna 1") {
            // Oblicz nowe kursy dla drużyny 1, drużyny 2 i remisu
            $newOdds1 = $team1_odds * (1 - $percentageChange);
            $newOdds2 = $team2_odds * (1 + $percentageChange);
            $newDrawOdds = $draw_odds * (1 + $percentageChange); // Zwiększ kurs remisu
            $updateSql = "UPDATE bets SET team1_odds = ?, team2_odds = ?, draw_odds = ? WHERE bet_id = ?";
        } elseif ($selectedResult == "Druzyna 2") {
            // Oblicz nowe kursy dla drużyny 1, drużyny 2 i remisu
            $newOdds1 = $team1_odds * (1 + $percentageChange);
            $newOdds2 = $team2_odds * (1 - $percentageChange);
            $newDrawOdds = $draw_odds * (1 - $percentageChange); // Zmniejsz kurs remisu
            $updateSql = "UPDATE bets SET team1_odds = ?, team2_odds = ?, draw_odds = ? WHERE bet_id = ?";
        } elseif ($selectedResult == "Remis") {
            // Oblicz nowe kursy dla drużyny 1, drużyny 2 i remisu
            $newOdds1 = $team1_odds * (1 - $percentageChange);
            $newOdds2 = $team2_odds * (1 - $percentageChange);
            $newDrawOdds = $draw_odds * (1 + $percentageChange); // Zwiększ kurs remisu
            $updateSql = "UPDATE bets SET team1_odds = ?, team2_odds = ?, draw_odds = ? WHERE bet_id = ?";
        }
    
        // Zapisz nowe kursy do bazy danych
        if (!empty($updateSql)) {
            $stmt = $this->conn->prepare($updateSql);
            $stmt->bind_param("dddi", $newOdds1, $newOdds2, $newDrawOdds, $betId);
            $stmt->execute();
        }
    }
    
    

    public function getUsername(): string | null {
        if (!$this->isLoggedIn()) return null;

        $id = $this->getUserIdFromToken($this->token);

        $sql = "SELECT * FROM users WHERE user_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["username"];
        }

        return null;
    }

    public function login(string $username, string $password): bool {
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($sql);

        $username = htmlspecialchars($username, ENT_QUOTES);
        $password = htmlspecialchars($password, ENT_QUOTES);

        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];
            $this->token = $this->generateToken();
            $expiration_date = date('Y-m-d H:i:s', strtotime('+1 day'));
            $this->saveTokenToDatabase($user_id, $this->token, $expiration_date);

            return true;
        }
    
        return false;
    }
    
    


    public function logout(): void {
        if ($this->token) {
            $token = $this->token;
            $sql = "DELETE FROM user_tokens WHERE token = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();
        }
    }

    public function register(string $username, string $password, int $is_admin): bool {
        // Sprawdzenie, czy nazwa użytkownika i hasło nie są puste
        if (empty($username) || empty($password)) {
            return false;
        }
    
        // Sprawdzenie, czy nazwa użytkownika już istnieje w bazie danych
        $checkUsername = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($checkUsername);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return false;
        }
        
        // Zabezpieczenie przed atakami XSS
        $username = htmlspecialchars($username, ENT_QUOTES);
        $password = htmlspecialchars($password, ENT_QUOTES);
        
        // Wstawianie użytkownika do bazy danych
        $sql = "INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $password, $is_admin);
        
        if ($stmt->execute()) {
            $this->token = $this->generateToken();
        } else {
            return false;
        }
        
        // Zapisanie tokenu do bazy danych
        $user_id = $this->conn->insert_id;
        $expiration_date = date('Y-m-d H:i:s', strtotime('+1 day'));
        $this->saveTokenToDatabase($user_id, $this->token, $expiration_date);
        
        return true;
    }
    
    

    public function isAdmin(): bool {
        if (!$this->isLoggedIn()) {
            return false;
        }
    
        $token = $this->token;
    
        $sql = "SELECT is_admin FROM users INNER JOIN user_tokens ON users.user_id = user_tokens.user_id WHERE token = ? AND expiration_date > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['is_admin'] == 1;
        } else {
            return false;
        }
    }
    public function payout($token, $amount) {
        // Pobierz identyfikator użytkownika na podstawie tokenu
        $userId = $this->getUserIdFromToken($token);
    
        // Pobierz aktualny stan konta użytkownika
        $sql = "SELECT balance FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Sprawdź czy udało się pobrać stan konta użytkownika
        if ($row = $result->fetch_row()) {
            $balance = $row[0];
    
            // Oblicz nowy stan konta użytkownika po dodaniu kwoty
            $newBalance = $balance + $amount;
    
            // Zaktualizuj stan konta użytkownika
            $sql = "UPDATE users SET balance = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("di", $newBalance, $userId);
            $stmt->execute();
    
            // Sprawdź czy aktualizacja się powiodła
            if ($stmt->affected_rows > 0) {
                return true; // Zwróć true jeśli wypłata się powiodła
            } else {
                return false; // Zwróć false jeśli wypłata się nie powiodła
            }
        } else {
            return false; // Zwróć false jeśli nie udało się pobrać stanu konta użytkownika
        }
    }
    
    
    

    public function addBet(string $event_name, string $event_name2, string $bet_type): bool {
        if (!$this->isAdmin()) return false;
        
        // Trimowanie danych wejściowych
        $event_name = trim($event_name);
        $event_name2 = trim($event_name2);
    
        // Sprawdzenie, czy dane nie są puste po trimowaniu
        if (empty($event_name) || empty($event_name2)) {
            return false;
        }
    
        // Pobranie ID użytkownika z tokena
        $user_id = $this->getUserIdFromToken($this->token); 
    
        // Zabezpieczenie przed atakami XSS
        $event_name = htmlspecialchars($event_name, ENT_QUOTES);
        $event_name2 = htmlspecialchars($event_name2, ENT_QUOTES);
        $bet_type = htmlspecialchars($bet_type, ENT_QUOTES);
    
        // Wstawienie zakładu do bazy danych
        $sql = "INSERT INTO bets (user_id, event_name, event_name2, bet_type) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $event_name, $event_name2, $bet_type);
    
        // Wykonanie zapytania i zwrócenie rezultatu
        return $stmt->execute();
    }
    

}
?>
