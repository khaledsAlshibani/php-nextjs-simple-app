<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function getProfile(): void
    {
        $result = $this->userService->getUserProfile();
        if ($result['status'] === 'error') {
            Response::sendError(
                $result['error']['message'],
                $result['error']['code'],
                [],
                $result['error']['errorCode']
            );
            return;
        }

        Response::sendSuccess($result['data'], $result['message']);
    }

    public function updatePassword(): void
    {
        $data = $this->getJsonInput();
        if (!$data) {
            Response::sendError('Invalid request payload', 400);
            return;
        }

        $result = $this->userService->handlePasswordUpdate($data);
        if ($result['status'] === 'error') {
            Response::sendError(
                $result['error']['message'],
                $result['error']['code'],
                $result['error']['details'] ?? [],
                $result['error']['errorCode']
            );
            return;
        }

        Response::sendSuccess(null, $result['message']);
    }

    public function deleteAccount(): void
    {
        $data = $this->getJsonInput();
        if (!$data) {
            Response::sendError('Invalid request payload', 400);
            return;
        }

        $result = $this->userService->handleDeleteAccount($data);
        if ($result['status'] === 'error') {
            Response::sendError(
                $result['error']['message'],
                $result['error']['code'],
                $result['error']['details'] ?? [],
                $result['error']['errorCode']
            );
            return;
        }

        Response::sendSuccess(null, $result['message']);
    }

    public function updateProfile(): void
    {
        $data = $this->getJsonInput();
        if (!$data) {
            Response::sendError('Invalid request payload', 400);
            return;
        }

        $result = $this->userService->handleProfileUpdate($data);
        if ($result['status'] === 'error') {
            Response::sendError(
                $result['error']['message'],
                $result['error']['code'],
                $result['error']['details'] ?? [],
                $result['error']['errorCode']
            );
            return;
        }

        Response::sendSuccess($result['data'], $result['message']);
    }

    public function updatePhoto(): void
    {
        if (!isset($_FILES['photo'])) {
            Response::sendError('No photo uploaded', 400, [], 'PHOTO_REQUIRED');
            return;
        }

        $result = $this->userService->handlePhotoUpdate($_FILES['photo']);
        if ($result['status'] === 'error') {
            Response::sendError(
                $result['error']['message'],
                $result['error']['code'],
                $result['error']['details'] ?? [],
                $result['error']['errorCode']
            );
            return;
        }

        Response::sendSuccess($result['data'], $result['message']);
    }
}
