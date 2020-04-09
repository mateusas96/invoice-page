<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-secondary">
                    <div class="card-header">
                        <ul class="nav nav-pills pc">
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Change name or email</a></li>
                            <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Change password</a></li>
                            <li class="nav-item"><a class="nav-link" href="#bankAccount" data-toggle="tab">Add bank account</a></li>
                            <li class="nav-item"><a class="nav-link" href="#removebankAccount" data-toggle="tab">Remove bank account</a></li>
                        </ul>
                        
                        <ul class="nav nav-pills for-mobile">
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Change name or email</a></li>
                            <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Change password</a></li>
                            <li class="nav-item"><a class="nav-link" href="#bankAccount" data-toggle="tab">Add bank account</a></li>
                            <li class="nav-item"><a class="nav-link" href="#removebankAccount" data-toggle="tab">Remove bank account</a></li>
                        </ul>     
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="bankAccount">
                            <form class="form-horizontal" v-on:submit.prevent="addBankAccount">
                                <div class="modal-body">
                                <div class="form-group row">
                                    <label for="inputBankAccount" class="col-sm-3 col-form-label">Add bank account<a class="important star">*</a></label>
                                    <div class="col-sm-7">
                                    <input id="bankAccountText" v-model="bankAccount.bank_account" type="text" name="bank_account"
                                        placeholder="New bank account"
                                        class="form-control" :class="{ 'is-invalid': bankAccount.errors.has('bank_account') }">

                                        <has-error :form="bankAccount" field="bank_account"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-3 col-sm-10">
                                    <button type="submit" class="btn btn-success" id="submitBankAccount">Submit</button>
                                    </div>
                                </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="removebankAccount">
                            <form class="form-horizontal" v-on:submit.prevent="loadBankAccounts">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <select placeholder="Select bank account" class="user form-control custom-select col-sm-6" id="chooseBankAccount" name="chooseBankAccount">
                                                <option value="0">Select bank account</option>
                                            </select>
                                        </div>
                                    <div class="form-group row">
                                        <div>
                                        <button type="submit" class="btn btn-success" id="submitRemoveBankAccount">Submit</button>
                                        </div>
                                    </div>
                                    </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="password">
                            <form class="form-horizontal" v-on:submit="updatePassword">
                                <div class="modal-body">
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">New password<a class="important star">*</a></label>
                                    <div class="col-sm-7">
                                    <input v-model="form.password" type="password" name="password"
                                        placeholder="Password"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">

                                        <has-error :form="form" field="password"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="confirmPassword" class="col-sm-3 col-form-label">Confirm password<a class="important star">*</a></label>
                                    <div class="col-sm-7">
                                    <input type="password" name="password" class="form-control" id="confirmPassword" placeholder="Password">
                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-3 col-sm-10">
                                    <button type="submit" class="btn btn-success" id="submitPassword">Submit</button>
                                    </div>
                                </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane active" id="settings">
                    <form class="form-horizontal" v-on:submit="updateProfile">
                        <div class="modal-body">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name<a class="important star">*</a></label>
                        <div class="col-sm-8">
                          <input v-model="form.name" type="text" name="name"
                            placeholder="Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">

                            <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email<a class="important star">*</a></label>
                        <div class="col-sm-8">
                          <input v-model="form.email" type="text" name="email"
                            placeholder="Email"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">

                            <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-success" id="submit">Submit</button>
                        </div>
                      </div>
                        </div>
                    </form>
                  </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.btn-success{
    font-size: 14px;
}
label .important.star{
    color: red
}
@media only screen and (max-width: 600px) {
  .nav-pills.pc{
      display: none !important;
  }
  .for-mobile .nav-item{
      font-size: 75%;
  }
}
@media only screen and (min-width: 601px) {
  .nav-pills.for-mobile{
      display: none !important;
  }
}
</style>
<script>
    export default {
        data(){
            return{
              bankAccount: new Form({
                  id: '',
                  bank_account: '',
              }),
              form: new Form({
                  id: '',
                  name: '',
                  email: '',
                  password: ''
              }),
            }
        },
        methods: {
            addBankAccount(){
                this.bankAccount.post('api/bankAccount')
                .then((res)=>{
                    this.bankAccount.bank_account = ''
                    Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Added bank account successfully!',
                    showConfirmButton: false,
                    timer: 4000
                    })
                    this.loadBankAccounts()
                })
                .catch((err)=>(console.log(err)))
            },
            loadBankAccounts(){
                $("#chooseBankAccount").empty()
                $('#chooseBankAccount').append('<option value=0>'+"Select bank account"+'</option>')
                axios.get('api/bankAccount')
                    .then(({data}) => {
                        var array = data
                        if (array != ''){
                            for (var i in array){
                                $('#chooseBankAccount').append("<option value="+array[i].id+">"+array[i].bank_account+"</option>")
                            }
                        }
                    })
                    .catch((err)=>{console.log(err)})
            },
            updateProfile(){
                this.form.put('api/updateProfile/'+this.form.id)
                .then((res)=>{
                    Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Your info updated successfully',
                    showConfirmButton: false,
                    timer: 4000
                })
                })
                .catch((err)=>(console.log(err)))
            },
            updatePassword(){
                let input = $("#inputPassword").val()
                let confirm = $("#confirmPassword").val()
                
                if(this.form.password === confirm && this.form.password != '' && confirm != ''){
                    this.form.put('api/updatePassword/'+this.form.id)
                    .then((res)=>{
                        Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Your password updated successfully',
                        showConfirmButton: false,
                        timer: 4000
                    })
                    })
                    .catch((err)=>{console.log(err)})
                }else if(this.form.password === undefined && confirm === ''){
                    Swal.fire({
                    icon: 'info',
                    title: 'Passwords fields ar empty!',
                    showConfirmButton: true,
                    })
                }else{
                    Swal.fire({
                    icon: 'info',
                    title: 'Passwords do not match!',
                    showConfirmButton: true,
                    })
                }
            }
        },
        mounted() {

        },
        created(){
            axios.get('api/profile')
            .then(({data}) => (this.form.fill(data)))
            .catch((err)=>{console.log(err)})
            this.loadBankAccounts()
        }
    }

    $(document).ready(function(){
        $('#submitRemoveBankAccount').click(function(){
            var selectedBankAcc = $('#chooseBankAccount').val()
            if(selectedBankAcc == '0'){
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'You cannot delete nothing!',
                showConfirmButton: true,
                timer: 4000
                })
            }else{
            axios.delete('api/bankAccount/'+selectedBankAcc)
            .then((res)=>{
                Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Your bank account was deleted successfully',
                showConfirmButton: false,
                timer: 4000
                })
            })
            .catch((err)=>(console.log(err)))
            }
        })
    })
</script>
