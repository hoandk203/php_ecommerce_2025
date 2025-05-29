<?php
class ChatbotController {
    private $db;
    private $chatbot;

    public function __construct() {
        require_once 'config/Database.php';
        require_once 'models/Chatbot.php';
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->chatbot = new Chatbot($this->db);
    }

    // API endpoint để nhận câu hỏi và trả về câu trả lời
    public function getResponse() {
        // Kiểm tra method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Lấy dữ liệu từ request
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['message'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Message is required']);
            return;
        }

        // Lấy câu trả lời
        $response = $this->chatbot->getResponse($data['message']);
        
        // Trả về response
        echo json_encode([
            'response' => $response
        ]);
    }

    // Thêm câu trả lời mới (chỉ dành cho admin)
    public function addResponse() {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['keywords']) || !isset($data['response'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Keywords and response are required']);
            return;
        }

        if ($this->chatbot->addResponse($data['keywords'], $data['response'])) {
            echo json_encode(['message' => 'Response added successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add response']);
        }
    }

    // API endpoint để lấy danh sách câu hỏi gợi ý
    public function getSuggestions() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $suggestions = $this->chatbot->getSuggestedQuestions();
        echo json_encode([
            'suggestions' => $suggestions
        ]);
    }
}
?> 