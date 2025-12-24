<?php

namespace App\Http\Controllers;

use App\Models\PropertyRental;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PropertyRentalController extends CrudController
{
  protected $table = 'property_rentals';

  protected $modelClass = PropertyRental::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }

  public function createOne(Request $request)
  {
    try {
      return DB::transaction(function () use ($request) {
        $data = $request->all();

        if (!isset($data['client_id'])) {
          $name = $request->input('name');
          $email = $request->input('email');
          $phone = $request->input('phone');
          $nic = $request->input('nic_number');
          $passp = $request->input('passport');

          if (!$name || !$email || !$phone || (!$nic && !$passp)) {
            return response()->json([
              'success' => false,
              'errors' => [__('property_rentals.required_fields_missing')],
            ]);
          }

          $existingUser = User::where('email', $email)->first();

          if ($existingUser) {
            $existingClient = Client::where('user_id', $existingUser->id)->first();

            if ($existingClient) {
              $data['client_id'] = $existingClient->id;
            } else {
              $client = Client::create([
                'user_id' => $existingUser->id,
                'nic_number' => $nic,
                'passport' => $passp,
              ]);
              $data['client_id'] = $client->id;
            }
          } else {
            $user = User::create([
              'name' => $name,
              'email' => $email,
              'phone' => $phone,
              'password' => bcrypt(Str::random(16)),
            ]);

            $client = Client::create([
              'user_id' => $user->id,
              'nic_number' => $nic,
              'passport' => $passp,
            ]);

            $data['client_id'] = $client->id;
          }
        }

        $request->replace($data);
        return parent::createOne($request);
      });
    } catch (ValidationException $e) {
      return response()->json(['success' => false, 'errors' => Arr::flatten($e->errors())]);
    } catch (\Exception $e) {
      \Log::error('PropertyRentalController::createOne error: ' . $e->getMessage());
      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  public function updateOne($id, Request $request)
  {
    try {
      return DB::transaction(function () use ($id, $request) {
        $data = $request->all();
        $rental = PropertyRental::findOrFail($id);

        if (
          isset($data['name']) || isset($data['email']) || isset($data['phone']) ||
          isset($data['nic_number']) || isset($data['passport'])
        ) {

          $client = $rental->renter;
          if ($client && $client->user) {
            $userData = [];
            if (isset($data['name'])) $userData['name'] = $data['name'];
            if (isset($data['email'])) $userData['email'] = $data['email'];
            if (isset($data['phone'])) $userData['phone'] = $data['phone'];

            if (!empty($userData)) {
              $client->user->update($userData);
            }

            $clientData = [];
            if (isset($data['nic_number'])) $clientData['nic_number'] = $data['nic_number'];
            if (isset($data['passport'])) $clientData['passport'] = $data['passport'];

            if (!empty($clientData)) {
              $client->update($clientData);
            }
          }
        }

        unset($data['name'], $data['email'], $data['phone'], $data['nic_number'], $data['passport']);

        $request->replace($data);
        return parent::updateOne($id, $request);
      });
    } catch (ValidationException $e) {
      return response()->json(['success' => false, 'errors' => Arr::flatten($e->errors())]);
    } catch (\Exception $e) {
      \Log::error('PropertyRentalController::updateOne error: ' . $e->getMessage());
      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }
}
