<?php
class Chatbot {
    private $conn;
    private $table_name = "chatbot_responses";
    
    // Khai báo các thuộc tính
    public $id;
    public $keywords;
    public $response;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả câu trả lời
    public function getAllResponses() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy câu trả lời theo ID
    public function getResponseById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->keywords = $row['keywords'];
            $this->response = $row['response'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Tìm câu trả lời phù hợp với câu hỏi
    public function getResponse($message) {
        // Chuyển câu hỏi về chữ thường để dễ so sánh
        $message = mb_strtolower($message, 'UTF-8');
        
        // Lấy tất cả các câu trả lời có sẵn
        $query = "SELECT keywords, response FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Tách các từ khóa thành mảng
            $keywords = explode(',', mb_strtolower($row['keywords'], 'UTF-8'));
            
            // Kiểm tra xem câu hỏi có chứa từ khóa nào không
            foreach ($keywords as $keyword) {
                if (strpos($message, trim($keyword)) !== false) {
                    return $row['response'];
                }
            }
        }
        
        // Trả về câu mặc định nếu không tìm thấy câu trả lời phù hợp
        return "Xin lỗi, tôi không hiểu câu hỏi của bạn. Vui lòng thử lại hoặc liên hệ hotline 0842500199.";
    }

    // Thêm câu trả lời mới
    public function addResponse($keywords, $response) {
        $query = "INSERT INTO " . $this->table_name . " (keywords, response) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$keywords, $response]);
    }

    // Cập nhật câu trả lời
    public function updateResponse($id, $keywords, $response) {
        $query = "UPDATE " . $this->table_name . " SET keywords = ?, response = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$keywords, $response, $id]);
    }

    // Xóa câu trả lời
    public function deleteResponse($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Lấy danh sách câu hỏi gợi ý
    public function getSuggestedQuestions() {
        $query = "SELECT keywords FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $suggestions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $keywords = explode(',', $row['keywords']);
            // Lấy từ khóa đầu tiên làm câu hỏi gợi ý
            $suggestions[] = ucfirst(trim($keywords[0])) . '?';
        }
        
        return $suggestions;
    }
}
?>