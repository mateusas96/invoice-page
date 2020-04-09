<template>
<div >
    <div class="container" v-if="isAdmin"> 
        <div class="row mt-4">
          <div class="col-12">
            <div class="card card-secondary">
              <div class="card-header">
                <div class="card-title users-title">Users list</div>

                <div class="card-tools row search-bar">
                  <div id="searchBar">
                    <input class="form-control form-control-sm"
                    type="search" placeholder="Search" aria-label="Search" v-on:keyup="searchit" v-model="search">
                    <div class="input-group-append">
                    </div>
                  </div>
                  <button class="btn btn-success add-new" v-on:click="newModal">
                      Add new <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Is disabled</th>
                      <th>Company</th>
                      <th>Type</th>
                      <th>Registered at</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users.data" :key="user.id">
                      <td>#</td> <!--{{user.id}}-->
                      <td>{{user.name}}</td>
                      <td>{{user.email}}</td>
                      <td v-if="user.accDisabled === 0" style="color:#12e665">False</td>
                      <td v-else style="color:red">True</td>
                      <td>{{user.companyName}}</td>
                      <td>{{user.type}}</td>
                      <td>{{user.created_at}}</td>
                      <td>
                          <a href="#" v-on:click="editModal(user)">
                          <i class="fa fa-edit"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="users" 
                v-on:pagination-change-page="getResults">
                </pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add new</h5>
        <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update user</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form v-on:submit.prevent="editmode ? updateUser() : createUser()">

      <div class="modal-body">

         <div class="form-group">Name | <small class="error" style="color:red">Required</small>
                <input v-model="form.name" type="text" name="name"
                placeholder="Name"
                 class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">

            <has-error :form="form" field="name"></has-error>
        </div> 
        
         <div class="form-group">Email address | <small class="error" style="color:red">Required</small>
                <input v-model="form.email" type="text" name="email"
                placeholder="Email address"
                 class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">

            <has-error :form="form" field="email"></has-error>
        </div> 
        
        <div class="form-group" v-if="editmode === false">Is user disabled? | <small class="error" style="color:red">Required</small>
                <select name="accDisabled" v-model="form.accDisabled" type="text" 
                 class="form-control" :class="{ 'is-invalid': form.errors.has('accDisabled') }">
                 <option value="">Is user disabled?</option>
                 <option value=1>Yes</option>
                 <option value=0>No</option>
                </select>
            <has-error :form="form" field="accDisabled"></has-error>
        </div> 

        <div class="form-group" v-else>Is user disabled? | <small class="error" style="color:red">Required</small>
                <select name="accDisabled" v-model="form.accDisabled" type="text" class="form-control">
                 <option :disabled="true" value="">Is user disabled?</option>
                 <option value=1>Yes</option>
                 <option value=0>No</option>
                </select>
        </div>

        <div class="form-group" v-if="editmode === false">Company | <small class="error" style="color:red">Required</small>
                <input v-model="form.companyName" type="text" name="Company"
                placeholder="Company"
                 class="form-control" :class="{ 'is-invalid': form.errors.has('companyName') }">
            <has-error :form="form" field="companyName"></has-error>
        </div> 

        <div class="form-group" v-else>Company | <small class="error" style="color:red">Required</small>
                <input v-model="form.companyName" type="text" name="Company"
                placeholder="Company"
                 class="form-control" :class="{ 'is-invalid': form.errors.has('companyName') }">
        </div>

        <div class="form-group" v-if="editmode ===false">User role | <small class="error" style="color:red">Required</small>
                <select name="type" v-model="form.type" type="text" 
                 class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                 <option value="">Select user role</option>
                 <option value="Admin">Admin</option>
                 <option value="Standart user">Standart user</option>
                </select>
            <has-error :form="form" field="type"></has-error>
        </div> 

        <div class="form-group" v-else>User role | <small class="error" style="color:red">Required</small>
                <select name="type" v-model="form.type" type="text" class="form-control">
                 <option :disabled="true" value="">Select user role</option>
                 <option value="Admin">Admin</option>
                 <option value="Standart user">Standart user</option>
                </select>
            <!-- <has-error :form="form" field="type"></has-error> -->
        </div> 

         <div class="form-group">Password | <small v-if="editmode === true" class="error" style="color:red">Disabled</small>
                 <small v-else class="error" style="color:red">Required</small>
                <input v-if="editmode === true" :disabled="true" v-model="form.password" type="password" name="password"
                placeholder="Password" class="form-control">
                 <input v-else v-model="form.password" type="password" name="password"
                placeholder="Password"
                 class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
            <has-error v-if="editmode === false" :form="form" field="password"></has-error>
        </div> 

      </div>
        <div class="modal-footer">
            <button v-show="!editmode" type="submit" class="btn btn-success">Create user</button>
            <button v-show="editmode" type="submit" class="btn btn-success">Update user</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>

      </form>


    </div>
  </div>
</div>
</div>
<div v-else class="message">
  <div class="alert alert-danger">{{users.message}}</div>
</div>
</div>

</template>
<style>
.message{
  margin-top: 50px;
}
.add-new{
  margin-left:10px;
  font-size:12px;
}
.btn{
  font-size:12px;
}
.search-bar{
  margin-right:20px;
  margin-top:10px;
}
#searchBar{
  width: 200px;
  border-radius:5px;
}

@media only screen and (max-width: 600px) {
  .search-bar{
  margin-right:0px;
  margin-top:10px;
  }
  #searchBar{
    width: 85px;
    border-radius:5px;
  }
}
@media only screen and (min-width: 601px) {
  .alert-danger{
    font-size: 50px;
    text-align: center;
    margin: auto;
    width: 1200px;
  }
}
</style>
<script>
        export default {
        data(){
            return{
              editmode: false,
              users: {},
              isAdmin: true,
              form: new Form({
                  id: '',
                  name: '',
                  email: '',
                  companyName: '',
                  accDisabled: '',
                  type: '',
                  password: ''
              }),
              search: ''
            }
        },
        methods: {
          searchit: _.debounce( () => {
            Fire.$emit('searching');
          }, 1000),
          	getResults(page = 1) {
              axios.get('api/user?page=' + page)
                .then(response => {
                  this.users = response.data;
                });
            },
            updateUser(){
              this.$Progress.start()
              this.form.put('api/user/'+this.form.id)
              .then(() =>{
                Fire.$emit('AfterCreate/Update')
                $('#addNew').modal('hide')
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'User info updated successfully',
                    showConfirmButton: false,
                    timer: 4000
                })
                this.$Progress.finish()
              })
              .catch(()=>{
                this.$Progress.fail()
              })
            },
            newModal(){
              this.editmode = false;
              this.form.reset();
              this.form.clear();
              $('#addNew').modal('show')
            },
            editModal(user){
              this.editmode = true;
              this.form.reset();
              this.form.clear();
              $('#addNew').modal('show')
              this.form.fill(user);
            },
            loadUser(){
              this.$Progress.start()
              axios.get("api/user")
              .then(({data}) => {
                this.users = data; this.$Progress.finish();
                if(data.message){
                  this.isAdmin = false
                }
              })
              .catch((err)=>{console.log(err); this.$Progress.fail()})
            },
            createUser(){
                this.$Progress.start()
                this.form.post('api/user')
                .then(() =>{
                Fire.$emit('AfterCreate/Update')
                $('#addNew').modal('hide')
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'User created successfully',
                    showConfirmButton: false,
                    timer: 4000
                })
                this.$Progress.finish()
              })
              .catch(()=>{
                this.$Progress.fail()
              })
            }
        },
        created() {
          Fire.$on('searching',()=>{
            let query=this.search;
            axios.get('api/findUser?q='+query)
            .then(({data})=>{
              this.users = data;
            })
            .catch(()=>{})
          })
            this.loadUser()
            Fire.$on('AfterCreate/Update',() => {this.loadUser()})
        },
        
    }

</script>
