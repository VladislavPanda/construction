<style>
    h3, h4{
        margin-left:20px;
    } 
</style>

<div style="background-color: white;" >
    <h3>Сведения о заработной плате на текущий момент: </h3>
    <h4>Текущая зарплата: {{ $salaryData['sum'] }}</h4>
    <h4>Оклад: {{ $salaryData['salary'] }}</h4>
    <h4>{{ $salaryData['info'] }}</h4>
</div>