<template>
<div >
    <div class="container"> 
        <div class="row mt-4">
          <div class="col-12">
            <div class="card card-secondary">
              <div class="card-header">
                <div class="card-title users-title">My Invoices</div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Invoice nr</th>
                      <th>Sender's company</th>
                      <th>Discount</th>
                      <th>Total price</th>
                      <th>Invoice currency</th>
                      <th>Invoice payment due date</th>
                      <th>Invoice paid at</th>
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
                      <td>{{invoice.invoice_payment_date}}</td>
                      <td v-if="invoice.invoice_paid_at === null">-</td>
                      <td v-else>{{invoice.invoice_paid_at}}</td>
                      <td v-if="invoice.is_paid === 0"><span class="badge bg-danger">Not Paid</span></td>
                      <td v-else><span class="badge bg-success" v-if="invoice.invoice_payment_date >= invoice.invoice_paid_at">Paid</span>
                      <span class="badge bg-warning" v-else-if="invoice.invoice_payment_date < invoice.invoice_paid_at">Paid</span></td>
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
          <u><strong>Invoice payment due date: </strong>{{inView.invoice_payment_date}}</u>
        </div>

        <div class="form-group">
          <strong>Notes: </strong><br>{{inView.notes}}
        </div>

        <div class="form-group">
          <strong>Terms: </strong><br>{{inView.terms}}
        </div>

        <div class="form-group table-responsive">
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

        <div class="form-group table-responsive">
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
          <div v-if="inView.is_paid===0"><strong>Total invoice price: </strong>{{inView.grand_total}} {{inView.invoice_currency}}</div>
          <div v-else><strike><strong>Total invoice price: </strong>{{inView.grand_total}} {{inView.invoice_currency}}</strike>
          <strong class="badge bg-success" v-if="inView.invoice_payment_date >= inView.invoice_paid_at"> PAID {{inView.invoice_paid_at}}</strong>
          <strong class="badge bg-warning" v-else-if="inView.invoice_payment_date < inView.invoice_paid_at"> PAID {{inView.invoice_paid_at}}</strong>
          </div>
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
.form-group:last-child .bg-success{
  margin-left: 1rem;
}
</style>

<script>
        export default {
        data(){
            return{
              hasBankAccount: false,
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
                  this.invoices = data
                  let fixedValue = this.invoices[0].grand_total
                  this.invoices[0].grand_total = fixedValue.toFixed(2)
              })
          },
          preview(INr){
            axios.get('api/invoiceView/'+INr)
            .then(({data})=>{
              this.invoiceView = data
              let fixedValue = this.invoiceView[0].grand_total
              this.invoiceView[0].grand_total = fixedValue.toFixed(2)
            })

            axios.get('api/invoiceItemsView/'+INr)
            .then(({data})=>{
              this.invoiceItemsView = data
            })
            
            $('#previewInvoice').modal('show')
          },
          payInvoice(INr){
            if(this.hasBankAccount){
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
            }else{
              Swal.fire({
                  toast: true,
                  position: 'top',
                  icon: 'warning',
                  title: 'You cannot pay, because You did not set up your bank account!',
                  timer: 4000,
                  showConfirmButton: false,
                })
            }
          },
        },
        created() {
            this.loadInvoices()
            axios.get('api/bankAccount')
            .then(({data})=>{
              if(data.length != 0){
                this.hasBankAccount = true
              }
            })
        },
        
    }
</script>