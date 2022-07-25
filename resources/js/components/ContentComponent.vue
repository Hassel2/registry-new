<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="text col-sm border border-warning rounded shadow text-center p-2 m-1">
                Лицензия
                </div>
                <div class="text col-sm border border-warning rounded shadow text-center p-2 m-1">
                Разработка
                </div>
                <div class="text col-sm border border-warning rounded shadow text-center p-2 m-1">
                Документы
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Лицензионный участок</th>
                        <th scope="col">...</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Местоположение</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Участок федерального значения</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Дата первоначальной регистрации лицензии</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Муниципальное образование</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Месторождение</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Лицензия</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Целевое назначение и вид работ</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Статус</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Ограничение по глубине горного отвода</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Площадь по документу (по координатам), км2</td>
                        <td>...</td>
                    </tr>
                    <tr>
                        <td>Проект разработки</td>
                        <td>...</td>
                    </tr>
                </tbody>
            </table>

            <hr class="bg-warning border-2 border-top border-danger">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Недропользователь</th>
                        <th scope="col">Номер</th>
                        <th scope="col">Дата регистрации</th>
                        <th scope="col">Срок действия</th>
                        <th scope="col">Состояние</th>
                    </tr>
                </thead>
                <tbody>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style scoped>
.text{
    background-color: rgb(214, 214, 214);
}

/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.container {
  width: clamp(40%, 300px);
  height: 630px;
  overflow: auto;
}
</style>