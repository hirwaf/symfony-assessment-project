<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Utility class for generating JSON responses.
 */
class ResponseUtil
{
    /**
     * Creates a success JsonResponse object.
     *
     * @param mixed $data The data to include in the response. Defaults to null.
     **/
    public static function success(mixed $data = null): array
    {
        $response = [
            'success' => true,
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return $response;
    }

    /**
     * Creates a JSON response with an error message and status code.
     *
     * @param string $message The error message. Default is 'An error occurred'.
     * @return array The JSON response object.
     */
    public static function error(string $message = 'An error occurred'): array
    {
        return [
            'success' => false,
            'error' => $message
        ];
    }

    /**
     * Creates a JSON response with a "Not Found" error message and status code 404 (HTTP_NOT_FOUND).
     *
     * @param string $message The error message. Default is 'Not found'.
     * @return array The JSON response object.
     */
    public static function notFound(string $message = 'Not found'): array
    {
        return self::error($message);
    }

    /**
     * Returns a JSON response for validation errors.
     *
     * @param ConstraintViolationListInterface $violations The list of validation violations
     *
     * @return array The JSON response object
     */
    public static function validationError(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return [
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ];
    }
}
