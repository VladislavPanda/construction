<?php

namespace App\Services;

use App\Models\User;
use App\Models\Speciality;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Db;

class SalaryService{
    public function getSalary(){
        $salaryData = [];
        $userId = Auth::user()->id;
        $role = Db::table('role_users')->select('role_id')->where('user_id', $userId)->get();
        $roleId = $role[0]->role_id;

        switch($roleId){
            case 2: $salaryData = $this->managerCalculator($userId);
            break;

            case 3: $salaryData = $this->workerCalculator($userId);
            break;

            case 4: $salaryData = $this->driverCalculator($userId);
            break;
            
            case 5: $salaryData = $this->foremanCalculator($userId);
            break;
        }

        print_r($salaryData);
    }

    private function managerCalculator($userId){
        return 'manager';
    }

    private function workerCalculator($userId){
        return 'worker';
    }

    private function driverCalculator($userId){
        return 'driver';
    }

    private function foremanCalculator($userId){
        return 'foreman';
    }
}