<?php

namespace App\Services;

use App\Common\Utility\ArrayUtil;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class BaseService {

  public static function create($data, Model $model, $isReturnData = false): array
  {
    $result = [
      'success' => false
    ];

    try {
      DB::beginTransaction();

      $model->fill($data)->save();

      $result['success'] = true;

      if ($isReturnData) {
        $result['created_data'] = $model;
      }

      DB::commit();
      $result['message'] = 'Successfully created.';
    } catch (Exception $e) {
      DB::rollBack();
      self::logError($e);
      $result['errors'] = 'Creation failed.';
    }

    return $result;
  }

    /**
     * @throws Exception
     */
    public static function update($data, $id, $model): array
    {
    $result = [
      'success' => false
    ];

    if (is_null($id)) throw new Exception('ID cannot be null.');

    try {
      DB::beginTransaction();
        $item = $model::find($id);
        $item->fill($data);

        $item->save();

      DB::commit();
      $result['success'] = true;
      $result['message'] = 'Successfully updated.';
    } catch (Exception $e) {
      DB::rollBack();
      self::logError($e);
      $result['errors'] = 'Update failed.';
    }

    return $result;
  }

  public static function datatableList($array, Builder $builder): array
  {
    $result = [
      'success' => false
    ];
    $rowsPerPage = ArrayUtil::valueAt($array, 'count');

    try {
      if (Config::get('app.env') === 'local') {
        Log::debug('rawQuery: ' . $builder->toRawSql());
      }

      $data = $builder->paginate($rowsPerPage);

      $result['data'] = $data;
      $result['success'] = true;
    } catch (Exception $e) {
      self::logError($e);
    }

    return $result;
  }

  public static function find($id, $model): array
  {
    $result = [
      'success' => false
    ];

    try {
      $query = $model->where('id', $id);

      if (Config::get('app.env') === 'local') {
        Log::debug('rawQuery: ' . $query->toRawSql());
      }

      $data = $query->first();

      $result['data'] = $data;
      $result['success'] = true;
    }
    catch (Exception $e) {
      self::logError($e);
    }

    return $result;
  }

  public static function delete($id, $model): array
  {
    $result = [
      'success' => false
    ];

    try {
      DB::beginTransaction();
      $query = $model->where('id', $id);
      $data = $query->first();
      $data->delete();

      DB::commit();
      $result['success'] = true;
    }
    catch (Exception $e) {
      DB::rollBack();
      self::logError($e);
    }

    return $result;
  }

  public static function batchInsert(array $data, Model $model): array
  {
    $result = [
      'success' => false
    ];

    try {
      DB::beginTransaction();
      $model->insert($data);

      DB::commit();
      $result['success'] = true;
      $result['message'] = 'Batch insertion successful.';
    } catch (Exception $e) {
      DB::rollBack();
      self::logError($e);
      $result['errors'] = 'Batch insertion failed.';
    }

    return $result;
  }

  public static function logError(Exception $e): void
  {
    $env = Config::get('app.env');
    $errorMessage = $e->getMessage();

    if ($env === 'uat') {
      Log::error("UAT Error: $errorMessage");
    } elseif ($env === 'local') {
      Log::error("Local Error: $errorMessage", [
        'trace' => $e->getTraceAsString(),
      ]);
    }
  }
}
