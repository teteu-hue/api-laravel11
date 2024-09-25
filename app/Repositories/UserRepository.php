<?php

namespace App\Repositories;

use App\DTO\Users\CreateUserDTO;
use App\Models\User;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function __construct(protected User $user)
    {  
        
    }

    public function getAll(int $perPage = 15, int $page = 1, string $filter = ''): LengthAwarePaginator
    {
        return $this->user->where(function($query) use ($filter){
            if($filter !== ''){
                $query->where('name', 'LIKE', "%{$filter}%");
            }
        })->paginate($perPage, ['*'], 'page', $page);
    }

    public function createNew(CreateUserDTO $dto): User
    {
        try{
            $data = (array) $dto;
            $data['password'] = bcrypt($data['password']);
            return $this->user->create($data);
        } catch(Exception $e){
            dd($e->getMessage());
        }
    }
}
