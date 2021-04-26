<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Exception;

class ApiException extends \Exception
{
    public function __construct($message = null, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = $this->parseMessageForHumans($this->getMessage());
    }

    protected function parseMessageForHumans(string $original_error_message): string
    {
        $index       = (int) (explode('.shipments[', $original_error_message)[1] ?? -1);
        if (-1 === $index) {
            return $original_error_message;
        }
        $api_message = explode(' Shipment validation error ', $original_error_message)[0];
        $request_str = '{' . explode('Request: {', $original_error_message)[1] ?? '}';
        $request_obj = json_decode($request_str, null);
        if (isset($request_obj->data, $request_obj->data->shipments[$index])) {
            $shipments   = $request_obj->data->shipments;
            $order_id    = $shipments[$index]->reference_identifier;
            $order_count = (string) count($shipments);
        }
        ob_start();
        printf('<strong>API refused %s shipments</strong><br/>', $order_count ?? 'these');
        if (isset($order_id)) {
            printf('Error for order id %s. ', $order_id);
        }
        echo 'Original message from API:<br/>';
        echo $api_message;
        return ob_get_clean();
    }
}
