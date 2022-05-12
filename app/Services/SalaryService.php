<?php

namespace App\Services;

use App\Models\User;
use App\Models\Speciality;
use App\Models\Bid;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Db;
use Carbon\Carbon;

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

        return $salaryData;
    }

    private function managerCalculator($userId){
        $sum = 0;

        // Извлекаем оклад
        $salary = Speciality::select('salary')->where('title', 'Менеджер')->get();
        $salary = $salary[0]->salary;

        $start = Carbon::now()->startOfMonth()->timezone('Europe/Moscow');
        $firstDateOfCurrentMonth = $start->toDateTimeString();

        $current = Carbon::now()->timezone('Europe/Moscow');
        $currentDate = $current->toDateTimeString();

        $bidsNum = Bid::whereBetween('created_at', [$firstDateOfCurrentMonth, $currentDate])->count();
        
        // Вопрос по формуле
        $sum = $salary + $bidsNum;
        
        return $sum;
    }

    private function workerCalculator($userId){
        return 'worker';
    }

    private function driverCalculator($userId){
        $sum = 0;

        // Извлекаем оклад
        $salary = Speciality::select('salary')->where('title', 'Водитель')->get();
        $salary = $salary[0]->salary;

        $start = Carbon::now()->startOfMonth()->timezone('Europe/Moscow');
        $firstDateOfCurrentMonth = $start->toDateTimeString();

        $current = Carbon::now()->timezone('Europe/Moscow');
        $currentDate = $current->toDateTimeString();

        $tasksNum = Task::where('status', 'Выполнено')->where('driver_id', $userId)->whereBetween('end_date', [$firstDateOfCurrentMonth, $currentDate])->count();
        
        $sum = $salary + $tasksNum * 10;

        return $sum;
    }

    private function foremanCalculator($userId){
        return 'foreman';
    }
}