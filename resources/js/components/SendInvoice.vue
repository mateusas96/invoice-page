<template>
<div class="container" id="invoiceApp" >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-secondary">
                <div class="card-header">
                    <div class="card-title invoice-title">Invoice tool</div>
                </div>
                    <div class="card-body">
                        <div id="date"><strong>Payment due date:</strong>  <input type="date" v-model="invoice.invoicePaymentDueDate" class="form-control" :class="{ 'is-invalid': invoice.errors.has('invoicePaymentDueDate') }"><has-error :form="invoice" field="invoicePaymentDueDate"></has-error></div>
                        <div class="section-spacer">
                            <input type="text" placeholder="Company Name" class="company-name form-control" v-model="invoice.company.name" :class="{ 'is-invalid': invoice.errors.has('company.name') }"><has-error :form="invoice" field="company.name"></has-error>
                            <textarea v-on:keyup="adjustTextAreaHeight" v-model="invoice.company.contact" class="form-control" :class="{ 'is-invalid': invoice.errors.has('company.contact') }"></textarea><has-error :form="invoice" field="company.contact"></has-error>
                        </div>
                        <div class="section-spacer">
                            <p><strong>Bill to:</strong><select id="billToClient" v-on:change="loadBillGettersInfo"><option value="0">Select client</option></select></p>
                            <textarea class="billToClientText form-control" v-on:keyup="adjustTextAreaHeight" v-model="invoice.client" placeholder="Client Information" :class="{ 'is-invalid': invoice.errors.has('client') }"></textarea><has-error :form="invoice" field="client"></has-error>
                        </div>
                <div>
                    <label for="currency-picker">Currency:</label>
                    <select id="currency-picker" v-model="invoice.invoiceCurrency">
                        <option v-for="currency in invoice.currencies" :key="currency.code" :value="currency">{{ currency.code }} - {{ currency.name }}</option>
                    </select>
                </div>
                <table class="responsive-table">
                    <thead ref="content">
                        <tr>
                            <th>No</th>
                            <th>Item</th>
                            <th>Price per unit</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tr v-for="(item, index) in invoice.items" :key="index">
                        <td data-label="No">{{ index + 1 }}</td>
                        <td data-label="Item"><input type="text" v-model="item.item_name" placeholder="Item name" class="form-control" :class="{ 'is-invalid': invoice.errors.has('items.'+index+'.item_name') }" /></td>
                        <td data-label="Price per unite"><div class="cell-with-input"><div>{{ invoice.invoiceCurrency.symbol }}</div><input type="number" min="0" v-model="item.unit_price" class="form-control" :class="{ 'is-invalid': invoice.errors.has('items.'+index+'.unit_price') }" /></div></td>
                        <td data-label="Quantity"><input type="number" min="0" v-model="item.quantity" class="form-control" :class="{ 'is-invalid': invoice.errors.has('items.'+index+'.quantity') }" /></td>
                        <td data-label="Total">{{ decimalDigits(item.unit_price * item.quantity) }}</td>
                        <td class="text-right"><button class="is-danger" v-on:click="deleteItem(index)">Delete item</button></td>
                    </tr>
                </table>
                <button v-on:click="addNewItem">Add item</button>
                <table>
                    <tr>
                        <td>Subtotal</td>
                        <td>{{ decimalDigits(subTotal) }}</td>
                    </tr>
                    <tr>
                        <td><div class="cell-with-input"><div>Discount</div> <input class="text-right" type="number" min="0" max="100" v-model="invoice.discountRate" /><div>%</div></div></td>
                        <td>{{ decimalDigits(discountTotal) }}</td>
                    </tr>
                    <tr>
                        <td><div class="cell-with-input"><div>Tax</div> <input class="text-right" type="number" min="0" max="100" v-model="invoice.taxRate" /><div>%</div></div></td>
                        <td>{{ decimalDigits(taxTotal) }}</td>
                    </tr>
                    <tr class="text-bold">
                        <td>Grand Total</td>
                        <td>{{ decimalDigits(grandTotal) }}</td>
                    </tr>
                </table>
                <div class="section-spacer">
                    <p>Notes:</p>
                    <textarea v-on:keyup="adjustTextAreaHeight" v-model="invoice.notes" class="form-control" :class="{ 'is-invalid': invoice.errors.has('notes') }"></textarea><has-error :form="invoice" field="notes"></has-error>
                </div>
                
                <div class="section-spacer">
                    <p>Terms:</p>
                    <textarea v-on:keyup="adjustTextAreaHeight" v-model="invoice.terms" class="form-control" :class="{ 'is-invalid': invoice.errors.has('terms') }"></textarea><has-error :form="invoice" field="terms"></has-error>
                </div>
                <button v-on:click="sendInvoice">Send Invoice</button>
                <button v-on:click="printInvoice">Print Invoice</button>
            </div>
                </div>
            </div>
        </div>
</div>
</template>

<style lang="scss" scoped>
$red: #ff5f6d;
$yellow: #ffc371;
$green: #81cf71;
$white: #faebd7;

.invoice-title{
    font-size: 50px;
    text-align: center;
}

input, select, textarea {
    background-color: transparentize($color: gray, $amount: 0.9);
    border-radius: 10px;
    border: 2px solid #dcdcdc;
    margin-top: 3px;
    margin-bottom: 3px;
    display: inline-block;
    transition: background-color 0.3s ease-in-out;
    width: 100%;

    &:hover {
        background-color: transparentize($color: white, $amount: 0.5);
    }

    @media print {
        background-color: transparent;
        border: none;
    }

    @media only screen and (min-width: 601px) {
        width: auto;
    }
}

textarea {
    margin-top: 5px;
    margin-bottom: 5px;
    width: 100%;
    min-height: 85px;

    &.billToClientText{
        min-height:110px;
    }
}

.section-spacer select{
    margin-left: 5px;
    @media only screen and (max-width: 600px){
        margin-left: 0px;
    }
}

select {
    @media 
    only screen and (max-width: 600px) {
        width: 100%;
    }

    @media print {
        appearance: none;
    }
}

.company-name {
    font-size: 2rem;
}

table{
    width: auto; 
    border-collapse: collapse; 
    margin: 15px 0px;
    
    thead{
        th {
            padding: 10px 15px;
        }
    }
    
    tr { 
        border-bottom: 1px solid $yellow;

        td {
            padding: 10px 10px;

            input {
                height: 35px;
                width: 100%;
            }

            .cell-with-input {
                display: flex;
                div {
                    margin: 5px;
                }
                input {
                    margin: 0px 5px;
                }
            }
        }
    }
}

.responsive-table {
    width: 100%;
    @media only screen and (max-width: 600px) {

        table, thead, tbody, th, td, tr { 
            display: block; 
        }

        thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr {
            padding: 2rem 0;
        }

        
        td[data-label] {
            position: relative;
            padding-left: 40%; 
            display: flex;
            align-items: center;

            &:before { 
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 35%; 
                padding-right: 10px; 
                white-space: nowrap;
                font-weight: bold;
            }
        }
    }
}

button {
    background-color: $green;
    border: none;
    border-radius: 100px;
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;

    &:focus {
        outline-color: $yellow;
        background-color: darken($color: $green, $amount: 7%);
    }

    &:hover {
        background-color: darken($color: $green, $amount: 5%);
    }

    @media print {
        display: none;
    }

    &.is-danger{
        background-color: $red;

        &:focus {
            background-color: darken($color: $red, $amount: 7%);
        }
    
        &:hover {
            background-color: darken($color: $red, $amount: 5%);
        }
    }
}

.text-right {
    text-align: right;
}

.text-bold {
    font-weight: bold;
}
</style>

<script>
export default {
        data() {
            return{
                invoice: new Form({
                    notes: '',
                    terms: '',
                    subTotalCost: '',
                    totalDiscount: '',
                    totalTax: '',
                    totalCost: '',
                    clientsId: '',
                    billSendersInfo: '',
                    clientInfo: '',
                    invoiceCurrency: {
                        'symbol': '€',
                        'name': 'Euro',
                        'symbol_native': '€',
                        'decimal_digits': 2,
                        'rounding': 0,
                        'code': 'EUR',
                        'name_plural': 'euros'
                    },
                    taxRate: 21,
                    discountRate: 0,
                    items: [
                        { 'item_name': '', 'quantity': 0, 'unit_price': 0, 'singleItemAllCost': 0},
                        { 'item_name': '', 'quantity': 0, 'unit_price': 0, 'singleItemAllCost': 0},
                    ],
                    currencies: [
                        {'symbol':'€','name':'Euro','symbol_native':'€','decimal_digits':2,'rounding':0,'code':'EUR','name_plural':'euros'},
                        {'symbol':'$','name':'US Dollar','symbol_native':'$','decimal_digits':2,'rounding':0,'code':'USD','name_plural':'US dollars'},
                    ],
                    company: {
                        'name': '',
                        'contact': ''
                    },
                    client: '',
                    invoicePaymentDueDate: '',
                    invoiceNumber: '',
                })
            }
        },
        methods: {
            addNewItem() {
                this.invoice.items.push(
                    { 'item_name': '', 'quantity': 0, 'unit_price': 0, 'singleItemAllCost': 0}
                )
            },
            loadClients(){
                axios.get('api/billToClientList')
                .then(({data})=>{
                    let array = data
                    this.invoice.clientInfo = data
                    if(data != ''){
                        for(let i in array){
                            $('#billToClient').append("<option value="+array[i].id+" > Company - "+array[i].companyName+"</option>")
                        }
                    }
                })
            },
            loadBillSendersInfo(){
                axios.get('api/billSendersInfo')
                .then(({data})=>{
                    this.invoice.billSendersInfo = data
                    this.invoice.company.name = this.invoice.billSendersInfo[0].companyName
                    this.invoice.company.contact = 'Name: '+this.invoice.billSendersInfo[0].name+',\nEmail: '+this.invoice.billSendersInfo[0].email+',\nBank Account: '+this.invoice.billSendersInfo[0].bank_account
                })
            },
            loadBillGettersInfo(clientId){
                if(clientId.target.value==0){this.invoice.client=''}
                for(let i in this.invoice.clientInfo){
                    if(clientId.target.value == this.invoice.clientInfo[i].id & this.invoice.clientInfo[i].bank_account != null){
                        this.invoice.clientsId = this.invoice.clientInfo[i].id
                        this.invoice.client = 'Client\'s name: '+this.invoice.clientInfo[i].name+',\nCompany\'s name: '+this.invoice.clientInfo[i].companyName+',\nEmail: '+this.invoice.clientInfo[i].email+',\nBank Account: '+this.invoice.clientInfo[i].bank_account
                    }else if (clientId.target.value == this.invoice.clientInfo[i].id & this.invoice.clientInfo[i].bank_account == null){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'You cannot send invoice to company '+this.invoice.clientInfo[i].companyName+', \n because they did not set up their bank account!',
                            showConfirmButton: true
                        })
                    }
                }
            },
            sendInvoice(){
                this.$Progress.start()  
                this.invoice.post('api/invoices')
                .then(()=>{
                    this.$Progress.finish()
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Invoice successfully sent!',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    })
                    window.setTimeout(function(){window.location.reload()},3000)
                })
                .catch(()=>{this.$Progress.fail()})
            },
            deleteItem(index) {
                this.invoice.items.splice(index, 1)
            },
            decimalDigits(value) {
                return this.invoice.invoiceCurrency.symbol + ' ' + value.toFixed(this.invoice.invoiceCurrency.decimal_digits)
            },
            printInvoice() {
                window.print()
            },
            adjustTextAreaHeight(event){
                let el = event.target
                el.style.height = "0px"
                el.style.height = (25+el.scrollHeight)+"px"
            }
        },
        computed: {
            subTotal() {
                let total = this.invoice.items.reduce(function(accumulator, item) {
                    accumulator + (item.singleItemAllCost = (item.unit_price * item.quantity))
                    return accumulator + (item.unit_price * item.quantity)
                }, 0)
                this.invoice.subTotalCost = total
                return total
            },
            discountTotal() {
                let total = this.subTotal * (this.invoice.discountRate / 100)
                this.invoice.totalDiscount = total
                return total
            },
            taxTotal() {
                let total = (this.subTotal - this.discountTotal) * (this.invoice.taxRate / 100)
                this.invoice.totalTax = total
                return total
            },
            grandTotal() {
                let total = (this.subTotal - this.discountTotal) + this.taxTotal
                this.invoice.totalCost = total
                return total
            }
        },
        created() {
            this.loadBillSendersInfo()
            this.loadClients()
            axios.get('api/invoiceNumber')
            .then(({data})=>{
                if(data.length !=0){
                    let newInvoiceNumber = data.split(/(\d+)/)
                    this.invoice.invoiceNumber = parseInt(newInvoiceNumber[1])+1
                }else{
                    this.invoice.invoiceNumber=100
                }
            })
        }
}
</script>