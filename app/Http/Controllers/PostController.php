<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class PostController extends CrudController
{
  protected $table = 'posts';
  protected $modelClass = Post::class;
  protected $restricted = ['create', 'update', 'delete'];

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }

  protected function afterReadOne($item, Request $request)
  {
    $item->load(['categories', 'tags', 'images.upload', 'agent.user']);
  }

  public function readOneBySlug($slug, Request $request)
  {
    try {
      if (in_array('read_one', $this->restricted)) {
        $user = $request->user();
        if (! $user->hasPermission($this->table, 'read')) {
          return response()->json([
            'success' => false,
            'errors'  => [__('common.permission_denied')],
          ]);
        }
      }

      $item = Post::where('slug', $slug)->first();

      if (! $item) {
        return response()->json([
          'success' => false,
          'errors'  => [__($this->table . '.not_found')],
        ]);
      }

      if (method_exists($this, 'afterReadOne')) {
        $this->afterReadOne($item, $request);
      }

      return response()->json([
        'success' => true,
        'data'    => ['item' => $item],
      ]);
    } catch (\Exception $e) {
      \Log::error('PostController::readOneBySlug error: ' . $e->getMessage());
      \Log::error($e->getTraceAsString());

      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }

  public function createOne(Request $request)
  {
    try {
      return DB::transaction(function () use ($request) {
        $user = $request->user();
        if (! $user->hasPermission('posts', 'create')) {
          return response()->json([
            'success' => false,
            'errors'  => [__('common.permission_denied')],
          ]);
        }

        $proto = new Post;
        $validated = $request->validate(
          $proto->rules(),
          method_exists($proto, 'validationMessages')
            ? $proto->validationMessages()
            : []
        );

        $post = Post::create($validated);

        $categoryIds = $request->input('category_ids', []);
        $tagIds      = $request->input('tag_ids', []);

        if (is_array($categoryIds)) {
          $post->categories()->sync($categoryIds);
        }

        if (is_array($tagIds)) {
          $post->tags()->sync($tagIds);
        }

        $imagePayload = collect($request->input('images', []))
          ->map(function (array $img) {
            return [
              'image_id' => $img['image_id'],
              'alt_text' => $img['alt_text'] ?? null,
              'order'    => $img['order']    ?? 0,
            ];
          })
          ->all();

        if (count($imagePayload)) {
          $post->images()->createMany($imagePayload);
        }

        if (method_exists($this, 'afterCreateOne')) {
          $this->afterCreateOne($post, $request);
        }

        $post->load(['categories', 'tags', 'images.upload', 'agent.user']);

        return response()->json([
          'success' => true,
          'data'    => ['item' => $post],
          'message' => __('posts.created'),
        ]);
      });
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors'  => Arr::flatten($e->errors()),
      ]);
    } catch (\Exception $e) {
      \Log::error('PostController::createOne error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }

  public function updateOne($id, Request $request)
  {
    try {
      return DB::transaction(function () use ($id, $request) {
        $user = $request->user();
        if (! $user->hasPermission('posts', 'update', $id)) {
          return response()->json([
            'success' => false,
            'errors'  => [__('common.permission_denied')],
          ]);
        }

        $proto = new Post;
        $validated = $request->validate(
          $proto->rules($id),
          method_exists($proto, 'validationMessages')
            ? $proto->validationMessages()
            : []
        );

        $post = Post::find($id);
        if (! $post) {
          return response()->json([
            'success' => false,
            'errors'  => [__('posts.not_found')],
          ]);
        }

        $post->update($validated);

        $categoryIds = $request->input('category_ids', []);
        $tagIds      = $request->input('tag_ids', []);

        if (is_array($categoryIds)) {
          $post->categories()->sync($categoryIds);
        }

        if (is_array($tagIds)) {
          $post->tags()->sync($tagIds);
        }

        $incoming = collect($request->input('images', []))
          ->map(function (array $img) {
            return [
              'image_id' => $img['image_id'],
              'alt_text' => $img['alt_text'] ?? null,
              'order'    => $img['order']    ?? 0,
            ];
          });

        $newImageIds = $incoming->pluck('image_id')->all();

        $post->images()
          ->whereNotIn('image_id', $newImageIds)
          ->get()
          ->each
          ->delete();

        foreach ($incoming as $img) {
          $existing = $post
            ->images()
            ->where('image_id', $img['image_id'])
            ->first();

          if ($existing) {
            $existing->update([
              'alt_text' => $img['alt_text'],
              'order'    => $img['order'],
            ]);
          } else {
            $post->images()->create($img);
          }
        }

        if (method_exists($this, 'afterUpdateOne')) {
          $this->afterUpdateOne($post, $request);
        }

        $post->load(['categories', 'tags', 'images.upload', 'agent.user']);

        return response()->json([
          'success' => true,
          'data'    => ['item' => $post],
          'message' => __('posts.updated'),
        ]);
      });
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors'  => Arr::flatten($e->errors()),
      ]);
    } catch (\Exception $e) {
      \Log::error('PostController::updateOne error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }
}
