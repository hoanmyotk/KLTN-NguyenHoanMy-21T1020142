<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\DoctorInfor;
use App\Models\Specialty;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = 'http://localhost:11434';
    }  
    
    public function askAI(string $prompt, callable $callback)
    {
        $responseBuffer = '';
        $client = new Client();
        $doctors = DoctorInfor::with([
                'user',
                'specialty',
                'priceRelation',
                'provinceRelation',
                'schedules',])  
                ->get()   
                ->map(function ($doctor) {
                        return [
                            'Họ và Tên' => $doctor->user->firstName . ' ' . $doctor->user->lastName,
                            'Chuyên khoa' => $doctor->specialty->name,
                            'Giá khám' => $doctor->priceRelation->valueVi,
                            'Học vị' => $doctor->provinceRelation->valueVi,
                            'Tên phòm khám' => $doctor->nameClinic,
                            'Địa chỉ khám' => $doctor->addressClinic,
                            'lịch khám' => $doctor->schedules->map(function ($schedule) {
                                            return [
                                                'Ngày khám' => $schedule->date,
                                                'Thời gian khám' => $schedule->timeTypeRelation->valueVi
                                            ];
                                        })->toArray()
                        ];})->toArray();
        $customData = [
            'Bác sĩ' => $doctors,
        ];
        $customDataString = json_encode($customData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        Log::info("Custom Data String: \n" . $customDataString);
        // Đọc dữ liệu từ file .txt
        $additionalInfo = file_get_contents(storage_path('app/bones.txt')) ?: "Không có thông tin bổ sung.";

        // Kết hợp dữ liệu từ database và file .txt thành prompt
        $enhancedPrompt = "Bạn là AI chat box của bệnh viện, đóng vai trò như một nhân viên tư vấn trực tuyến. Bạn chỉ được phép sử dụng thông tin sau để trả lời câu hỏi của người dùng:\n\n
        ### Dữ liệu của bác sĩ:\n{$customDataString}\n\n
        ### Thông tin bổ sung từ file:\n{$additionalInfo}\n\n
        Câu hỏi từ người dùng: {$prompt}\n\n
        Yêu cầu bắt buộc khi trả lời:\n
        - Nếu bệnh nhân nêu triệu chứng và hỏi nên khám bác sĩ nào hay chuyên khoa nào thì cứ dùng thông tin database để trả lời phù hợp
        - Nếu câu hỏi KHÔNG liên quan đến dữ liệu được cung cấp HOẶC không thể trả lời chính xác dựa trên dữ liệu, hãy trả lời đúng chính xác cụm sau (không thêm gì cả): 'Câu hỏi không liên quan đến chúng tôi!',
        - Nếu bệnh nhân hỏi lịch khám của bác sĩ hãy trả lời bằng danh sách lịch khám đang có trên dữ liệu cung cấp.
        - Trả lời ngắn gọn, chính xác và CHỈ dựa trên các dữ liệu đã được cung cấp ở trên.
        - Nếu bệnh nhân hỏi về lịch khám của bác sĩ thì cứ dựa vào dữ liệu đã cung cấp ở trên mà trả lời.
        - KHÔNG được trả lời dưới bất kỳ định dạng nào như JSON.
        - Hãy dùng giọng điệu thân thiện, rõ ràng, dễ hiểu như một nhân viên hỗ trợ tại bệnh viện.";
        
        try {
            $response = $client->post($this->baseUrl . '/api/generate', [
                'json' => [
                    'model' => 'gemma2:2b',
                    'prompt' => $enhancedPrompt, 
                ],
                'stream' => true,
                'buffer' => false,
                'decode_content' => true,
                'http_errors' => false
            ]);

            $body = $response->getBody();
            $partialData = '';

            while (!$body->eof()) {
                $chunk = $body->read(256);
                if (empty($chunk)) continue;

                $partialData .= $chunk;
                $parts = explode("\n", $partialData);
                $partialData = array_pop($parts); // Giữ phần cuối lại để ghép tiếp

                foreach ($parts as $part) {
                    $part = trim($part);
                    if (empty($part)) continue;

                    try {
                        $json = json_decode($part, true);
                        if ($json && isset($json['response'])) {
                            $token = $json['response'];
                            $responseBuffer .= $token;
                            echo "data: " . json_encode(['message' => $responseBuffer]) . "\n\n";
                            ob_flush();
                            flush();
                        }

                        // Kiểm tra nếu đã hoàn thành tin nhắn
                        if (isset($json['done']) && $json['done'] === true) {
                            echo "event: done\ndata: {}\n\n";
                            ob_flush();
                            flush();
                            break;
                        }
                    } catch (\Exception $e) {
                        // Nếu lỗi JSON, bỏ qua và tiếp tục
                    }
                }
            }
        } catch (\Exception $e) {
            $callback('Không thể kết nối đến AI. Vui lòng thử lại sau.');
        }
    }
}