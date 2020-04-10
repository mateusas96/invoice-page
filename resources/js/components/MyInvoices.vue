<template>
<div >
    <div class="container"> 
        <div class="row mt-4">
          <div class="col-12">
            <div class="card card-secondary">
              <div class="card-header">
                <div class="card-title users-title">My Invoices</div>

                <div class="card-tools row search-bar">
                  <div id="searchBar">
                    <!-- <input class="form-control form-control-sm"
                    type="search" placeholder="Search" aria-label="Search" v-on:keyup="searchit" v-model="search"> -->
                    <div class="input-group-append">
                    </div>
                  </div>
                  <!-- <button class="btn btn-success add-new" v-on:click="newModal">
                      Add new <i class="fas fa-user-plus"></i></button> -->
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Invoice nr</th>
                      <th>Sender's company</th>
                      <th>Discount</th>
                      <th>Total price</th>
                      <th>Invoice currency</th>
                      <th>Invoice date</th>
                      <th>Payment status</th>
                      <th>Preview invoice</th>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="invoice in invoices" :key="invoice.invoice_number">
                      <td>{{invoice.invoice_number}}</td>
                      <td>{{invoice.senders_company}}</td>
                      <td>{{invoice.discount}}</td>
                      <td>{{invoice.grand_total}}</td>
                      <td>{{invoice.invoice_currency}}</td>
                      <td>{{invoice.invoice_date}}</td>
                      <td v-if="invoice.is_paid === 0"><span class="badge bg-danger">Not Paid</span></td>
                      <td v-else><span class="badge bg-success">Paid</span></td>
                      <td>
                          <a href="#" v-on:click="preview(invoice.invoice_number)">
                          <i class="fa fa-eye"></i>
                          </a>
                      </td>
                      <td v-if="invoice.is_paid === 0"><a href="#" v-on:click="payInvoice(invoice.invoice_number)" class="badge bg-primary">Pay</a></td>
                      <td v-else></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <!-- <pagination :data="users" 
                v-on:pagination-change-page="getResults">
                </pagination> -->
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
<div class="modal fade" id="previewInvoice" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invoice preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>

      <form v-for="inView in invoiceView" :key="inView.id">

      <div class="modal-body">

        <div class="form-group">
          <strong>Invoice number: </strong>{{inView.invoice_number}}
        </div> 
        
        <div class="form-group">
          <strong>Sender's company name: </strong>{{inView.senders_company}}
        </div> 
        
        <div class="form-group">
          <strong>Sender's information: </strong><br>{{inView.senders_info}}
        </div>

        <div class="form-group">
          <strong>Invoice date: </strong>{{inView.invoice_date}}
        </div>

        <div class="form-group">
          <strong>Notes: </strong><br>{{inView.notes}}
        </div>

        <div class="form-group">
          <strong>Terms: </strong><br>{{inView.terms}}
        </div>

        <div class="form-group">
          <table class="table table-bordered">
            <tr>
              <th>Item name</th>
              <th>Quantity</th>
              <th>Price per unit</th>
              <th>Total price</th>
              <th>Currency</th>
            </tr>
            <tr v-for="invoiceItems in invoiceItemsView" :key="invoiceItems.id">
              <td>{{invoiceItems.item_name}}</td>
              <td>{{invoiceItems.quantity}}</td>
              <td>{{invoiceItems.price_per_unit}}</td>
              <td>{{invoiceItems.total}}</td>
              <td>{{inView.invoice_currency}}</td>
            </tr>
          </table>
        </div>

        <div class="form-group">
          <table class="table table-bordered">
            <tr>
              <th>Total discount in %</th>
              <th>Total discount in {{inView.invoice_currency}}</th>
              <th>Total tax in %</th>
              <th>Total tax in {{inView.invoice_currency}}</th>
              <th>Total invoice price</th>
            </tr>
            <tr>
              <td>{{inView.discount_in_percentage}}</td>
              <td>{{inView.discount}}</td>
              <td>{{inView.tax_in_percentage}}</td>
              <td>{{inView.tax}}</td>
              <td>{{inView.grand_total}}</td>
            </tr>
          </table>
        </div>

        <div class="form-group">
          <strong>Total invoice price: </strong>{{inView.grand_total}} {{inView.invoice_currency}}
        </div>

      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" v-if="inView.is_paid===0" v-on:click="payInvoice(inView.invoice_number)">Pay</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </form>


    </div>
  </div>
</div>
</div>
</div>

</template>

<style lang="scss" scoped>
.badge{
    font-size:16px;
}
.fa-eye{
    font-size:20px;
}
.btn{
  font-size:12px;
}
</style>

<script>
        export default {
        data(){
            return{
              invoices: {},
              invoiceView: {},
              invoiceItemsView: {},
              search: ''
            }
        },
        methods: {
          loadInvoices(){
              axios.get('api/invoices')
              .then(({data})=>{
                  this.invoices = data.data
                  let fixedValue = this.invoices[0].grand_total
                  this.invoices[0].grand_total = fixedValue.toFixed(2)
              })
          },
          preview(INr){
            axios.get('api/invoiceView?get='+INr)
            .then(({data})=>{
              this.invoiceView = data
              let fixedValue = this.invoiceView[0].grand_total
              this.invoiceView[0].grand_total = fixedValue.toFixed(2)
            })

            axios.get('api/invoiceItemsView?get='+INr)
            .then(({data})=>{
              this.invoiceItemsView = data
            })
            
            $('#previewInvoice').modal('show')
          },
          payInvoice(INr){
            this.$Progress.start()
            axios.put('api/invoices/'+INr)
            .then(()=>{
              Swal.fire({
                toast: true,
                position: 'top',
                icon: 'success',
                title: 'Invoice successfully paid!',
                timer: 2000,
                showConfirmButton: false,
              })
              this.$Progress.finish()
              this.loadInvoices()
            })
          },
        },
        created() {
            this.loadInvoices()
        },
        
    }
</script>