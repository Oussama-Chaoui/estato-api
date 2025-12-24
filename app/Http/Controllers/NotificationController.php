<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
  public function index(Request $request)
  {
    try {
      $user = Auth::user();
      $perPage = $request->get('per_page', 15);

      $notifications = $user->notifications()
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

      return response()->json([
        'success' => true,
        'data' => [
          'items' => $notifications->items(),
          'meta' => [
            'current_page' => $notifications->currentPage(),
            'last_page' => $notifications->lastPage(),
            'total_items' => $notifications->total(),
          ],
        ],
      ]);
    } catch (\Exception $e) {
      Log::error('Error caught in function NotificationController.index: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  public function unreadCount()
  {
    try {
      $user = Auth::user();
      $count = $user->unreadNotifications()->count();

      return response()->json([
        'success' => true,
        'data' => ['count' => $count]
      ]);
    } catch (\Exception $e) {
      Log::error('Error caught in function NotificationController.unreadCount: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  public function markAsRead($id)
  {
    try {
      $user = Auth::user();
      $notification = $user->notifications()->find($id);

      if (!$notification) {
        return response()->json([
          'success' => false,
          'errors' => [__('notifications.not_found')]
        ], 404);
      }

      $notification->markAsRead();

      return response()->json([
        'success' => true,
        'data' => ['item' => $notification],
        'message' => __('notifications.marked_as_read')
      ]);
    } catch (\Exception $e) {
      Log::error('Error caught in function NotificationController.markAsRead: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  public function markAllAsRead()
  {
    try {
      $user = Auth::user();
      $user->unreadNotifications()->update(['read_at' => now()]);

      return response()->json([
        'success' => true,
        'message' => __('notifications.all_marked_as_read')
      ]);
    } catch (\Exception $e) {
      Log::error('Error caught in function NotificationController.markAllAsRead: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  /**
   * Delete a notification
   */
  public function destroy($id)
  {
    try {
      $user = Auth::user();
      $notification = $user->notifications()->find($id);

      if (!$notification) {
        return response()->json([
          'success' => false,
          'errors' => [__('notifications.not_found')]
        ], 404);
      }

      $notification->delete();

      return response()->json([
        'success' => true,
        'message' => __('notifications.deleted')
      ]);
    } catch (\Exception $e) {
      Log::error('Error caught in function NotificationController.destroy: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  /**
   * Delete all notifications
   */
  public function destroyAll()
  {
    try {
      $user = Auth::user();
      $user->notifications()->delete();

      return response()->json([
        'success' => true,
        'message' => __('notifications.all_deleted')
      ]);
    } catch (\Exception $e) {
      Log::error('Error caught in function NotificationController.destroyAll: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }
}
