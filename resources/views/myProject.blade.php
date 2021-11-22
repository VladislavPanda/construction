<style>
    h3, h4{
        margin-left:20px;
    } 
</style>

<div style="background-color: white;" >
    <h3>Сведения об объекте: </h3>
    <h4>Адрес: {{ $projectInfo[0]['address']}}</h4>
    <h4>Описание: {{ $projectInfo[0]['description'] }}</h4>
    <h4>Дата завершения: {{ $projectInfo[0]['end_date'] }}</h4>
    <h4>Статус: {{ $projectInfo[0]['status'] }}</h4>
    <h4 style="margin-bottom: 30px;">Дата создания: {{ $projectInfo[0]['created_at'] }}</h4>
</div>