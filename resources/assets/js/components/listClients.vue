<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div>
        <div class="row" style="margin-bottom: 10px;margin-left: 0px">
            <label class="pull-left" style="font-size: 20px">
                Registered Clients
            </label>
            <i class="fa fa-question-circle pull-right" style="color: darkblue;font-size: 20px;cursor: pointer" @click="helpOn()"></i>
        </div>
        <div class="row">
            <div class="row alert alert-info" v-show="help">
                <button type="button" class="close" @click="helpOff()">&times;</button>
                <header>Help information</header>
                <p>
                <ol>
                    <li>This Page displays all the CLients registered in the System</li>
                    <li>Further details can be viewed by clicking on the Edit Button of each client, of which edit operations can be performed also</li>
                </ol>
                </p>
            </div>
        </div>
        <table class="table table-striped table-bordered dt-responsive" id="vueTable"
               cellspacing="0" width="100%" style="font-size: 10px">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Other Name(s)</th>
                <th>D.O.B</th>
                <th>Type</th>
                <th>Gender</th>
                <th>Id No.</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="client in clients">
                <td>{{ client.first_name}}</td>
                <td>{{ client.other_names}}</td>
                <td>{{ client.yob}}</td>
                <td>{{ client.type}}</td>
                <td>{{ client.gender}}</td>
                <td>{{ client.id_number}}</td>
                <td>
                    <a class="btn btn-success" @click="viewClient(client)">
                        <i class="fa fa-edit">
                            Edit
                        </i>
                    </a>
                </td>
            </tr>

            </tbody>
        </table>

        <transition name="modal">
            <div class="modal-mask" v-show="clientModal">
                <div class="modal-wrapper">
                    <div class="modal-container" v-if="currentClient.id" style="width: 50% !important">

                        <div class="modal-header">
                            <slot name="header">
                                <label>
                                    {{currentClient.first_name}}, {{currentClient.other_names}}
                                </label>
                                <h6 class="pull-right">
                                    last updated on: {{currentClient.updated_at}}
                                </h6>
                            </slot>
                        </div>

                        <div class="modal-body" style="max-height: 350px; overflow-y: scroll">
                            <slot name="body">
                                <hr>
                                <label>Personal Information</label>
                                <div class="form-group">
                                    <h6>First Name:</h6>
                                    <input type="text" v-model="currentClient.first_name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Other Name(s):</h6>
                                    <input type="text" v-model="currentClient.other_names" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Date of Birth:</h6>
                                    <input type="text" v-model="currentClient.yob" class="form-control">
                                </div>
                                <hr>
                                <label>Contact Information</label>

                                <div class="form-group">
                                    <h6>Phone Number:</h6>
                                    <input type="text" v-model="currentClient.phone" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Email Address:</h6>
                                    <input type="text" v-model="currentClient.email" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Physical Address:</h6>
                                    <input type="text" v-model="currentClient.address" class="form-control">
                                </div>
                                <hr>
                                <label>Next-of-Keen Information</label>

                                <div class="form-group">
                                    <h6>Keen Type:</h6>
                                    <input type="text" v-model="currentClient.keen_type" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Keen Name:</h6>
                                    <input type="text" v-model="currentClient.keen_name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Keen Contacts:</h6>
                                    <input type="text" v-model="currentClient.keen_contact" class="form-control">
                                </div>

                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-success" @click="editClient(currentClient)">
                                    {{updateButton}}
                                </button>
                                <button class="btn btn-danger" @click="closeModal()">
                                    Cancel
                                </button>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        mounted: function () {
            console.log('Component mounted.');
            this.allClients();
        },
        data: function () {
            return {
                clients: [],
                clientModal: false,
                currentClient: [],
                updateButton: 'Update',
                help: false
            }
        },
        methods: {
            helpOn: function () {
                var inheritance = this;
                inheritance.help = true;
            },
            helpOff: function () {
                var inheritance = this;
                inheritance.help = false;
            },
            openModal: function () {
                var inheritance = this;
                inheritance.clientModal = true;
            },
            closeModal: function () {
                var inheritance = this;
                inheritance.clientModal = false;
            },
            allClients: function () {
                var inheritance = this;
                axios.get(base_url + '/clients/all')
                    .then(function (response) {
                        inheritance.clients = response.data;
                    }.bind(this));
                setTimeout(function () {
                    $("#vueTable").DataTable();
                }, 500);
            },
            viewClient: function (client) {
                var inheritance = this;
                console.log(client.id);
                axios.get(base_url + '/clients/' + client.id)
                    .then(function (response) {
                        inheritance.currentClient = response.data;
                        inheritance.clientModal = true;
                    }.bind(this));

            },
            editClient: function (client) {
                var inheritance = this;
                inheritance.updateButton = 'Updating...';
                axios.post(base_url + '/clients/edit', client)
                    .then(function (response) {
                        inheritance.updateButton = "Update";
                        inheritance.allClients();
                        inheritance.closeModal();
                    }.bind(this))
            }

        }
    }
</script>
