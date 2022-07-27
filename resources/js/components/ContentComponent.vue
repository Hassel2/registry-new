<template>
    <div>
        <div class="container">
            <!-- <div class="row">
                <div class="text col-sm border border-warning rounded shadow text-center p-2 m-1">
                Лицензия
                </div>
                <div class="text col-sm border border-warning rounded shadow text-center p-2 m-1">
                Разработка
                </div>
                <div class="text col-sm border border-warning rounded shadow text-center p-2 m-1">
                Документы
                </div>
            </div> -->

            <h3 class="m-1">{{ number }}</h3>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Федеральный орган по лицензированию</td>
                        <td>{{ federal_licensing_authority }}</td>
                    </tr>
                    <tr>
                        <td>Статус</td>
                        <td>{{ status }}</td>
                    </tr>
                    <tr>
                        <td>Предыдущая лицензия</td>
                        <td> 
                            <span class="text-primary" @click="nextLicence(prev_license_id)">
                                {{ prev_license }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Дата получения</td>
                        <td>{{ receiving_date }}</td>
                    </tr>
                    <tr>
                        <td>Дата аннулирования</td>
                        <td>{{ cancellation_date }}</td>
                    </tr>
                    <tr>
                        <td>Дата окончания</td>
                        <td>{{ expiration_date }}</td>
                    </tr>
                </tbody>
            </table>

            <hr class="bg-warning border-2 border-top border-danger">

            <template v-if="field.length != 0">
                <h3 class="py-2 m-1">{{ field.name }} Месторождение</h3>

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Степень освоения</td>
                            <td>{{ field.degree }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Местоположение:</td>
                        </tr>
                        <template v-for="position in field.position">
                            <tr v-if="position.subject != null || position.federal_district != null">
                                <td>{{ position.subject }}</td>
                                <td>{{ position.federal_district }}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </template>

        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            number: "-",
            federal_licensing_authority: "-",
            status: "-",
            prev_license_id: "-",
            prev_license: "-",
            receiving_date: "-",
            cancellation_date: "-",
            expiration_date: "-",
            field: [],
        }
    },

    methods: {
        GetLicension(id) {
            axios.get(`/api/license${id}/getCard`).then(response => {
                var license = response.data.data[0]
                this.number = license.number
                this.federal_licensing_authority = license.federal_licensing_authority
                this.status = license.status
                this.prev_license = license.prev_license
                this.prev_license_id = license.prev_license_id
                this.receiving_date = license.receiving_date
                this.cancellation_date = license.cancellation_date
                this.expiration_date = license.expiration_date
                if (license.field != null) this.field = license.field[0]
                else this.field = []
            })
        },

        nextLicence(id) {
            if (id != null && id != "-") this.GetLicension(id)
        }
    },

}
</script>

<style scoped>
.text {
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