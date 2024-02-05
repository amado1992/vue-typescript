<?php


namespace App\Utils;


use App\Models\Traza;
use App\Repositories\TrazaRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventsUtil
{
    /**
     * @param string $message
     * @param mixed $data
     *
     * @return array
     */
    public static function makeResponse($message, $data)
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return array
     */
    public static function makeError($message, $error)
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($error)) {
            $res['error'] = $error;
        }

        return $res;
    }

    public static function makeWarning($message, $warning)
    {
        $res = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty($warning)) {
            $res['warning'] = $warning;
        }

        return $res;
    }

    public static function callBack($columns, $operation, $value)
    {
        return function ($query) use ($columns, $operation, $value) {
            $query->where($columns, $operation, $value);
        };
    }

    public static function createToken($username, $password)
    {
        return $username . $password . now() . Str::random(60);
    }

    public static function getUserId()
    {
        return (auth()->user() === null) ? session()->get('user.id') : auth()->id();
    }


}
