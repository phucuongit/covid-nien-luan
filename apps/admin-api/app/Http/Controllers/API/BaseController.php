<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message = '',  $code = 200)
    {
    	$response = [
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data'    => $result,
        ];
        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errorMsg, $errorLog = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'code' => $code,
            'message' => $errorMsg,
            'errors' => $errorLog
        ];
        return response()->json($response, $code);
    }
}
// 200: Ok. Mã cơ bản có ý nghĩa là thành công trong hoạt động.
// 201: Đối tượng được tạo, được dùng trong hàm store.
// 204: Không có nội dung trả về. Hoàn thành hoạt động nhưng sẽ không trả về nội dung gì.
// 206: Trả lại một phần nội dung, dùng khi sử dụng phân trang.
// 400: Lỗi. Đây là lỗi cơ bản khi không vượt qua được xác nhận yêu cầu từ server.
// 401: Unauthorized. Lỗi do yêu cầu authen.
// 403: Forbidden. Lỗi này người dùng vượt qua authen, nhưng không có quyền truy cập.
// 404: Not found. Không tìm thấy yêu cầu tương tứng.
// 500: Internal server error.
// 503: Service unavailable.