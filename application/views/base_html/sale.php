<div class="container-fluid">
   <div class="row">
      <ul class="cbp-vimenu">
        <li data-toggle="tooltip"  data-html="true" data-placement="left" title="CloseRegister"><a href="javascript:void(0)" onclick="CloseRegister()"><i class="fa fa-times" aria-hidden="true"></i></a></li>
        <li data-toggle="tooltip"  data-html="true" data-placement="left" title="switshregister"><a href="pos/switshregister"><i class="fa fa-random" aria-hidden="true"></i></a></li>
      </ul>
      <div class="col-md-5 left-side">
         <div class="row">
            <div class="row row-horizon">
               <span class="holdList">
                  <!-- list Holds goes here -->
               </span>
               <span class="Hold pl" onclick="AddHold()">+</i></span>
               <span class="Hold pl" onclick="RemoveHold()">-</span>
            </div>
         </div>
         <div class="col-xs-8">
            <h2>Choose Client</h2>
         </div>
         <div class="col-xs-4 client-add">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#AddCustomer">
               <span class="fa-stack fa-lg" data-toggle="tooltip" data-placement="top" title="Add New Customer">
                  <i class="fa fa-square fa-stack-2x grey"></i>
                  <i class="fa fa-user-plus fa-stack-1x fa-inverse dark-blue"></i>
               </span>
            </a>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#ticket">
               <span class="fa-stack fa-lg" data-toggle="tooltip" data-placement="top" title="Show Last Receipt">
                  <i class="fa fa-square fa-stack-2x grey"></i>
                  <i class="fa fa-ticket fa-stack-1x fa-inverse dark-blue"></i>
               </span>
            </a>
         </div>
         <div class="col-sm-12">
            <select class="js-select-options form-control" id="customerSelect">
              <option value="0">Walk In Customer</option>

            </select>
         </div>
         <div class="col-sm-12">
            <form onsubmit="return barcode()">
               <input type="text" autofocus id="" class="form-control barcode" placeholder="Barcode Scanner">
            </form>
         </div>
         <div class="col-xs-5 table-header">
            <h3>Product</h3>
         </div>
         <div class="col-xs-2 table-header">
            <h3>Header</h3>
         </div>
         <div class="col-xs-3 table-header nopadding">
            <h3 class="text-left">Quantity</h3>
         </div>
         <div class="col-xs-2 table-header nopadding">
            <h3>Total</h3>
         </div>
         <div id="productList">
            <!-- product List goes here  -->
<div class="col-xs-12">
    <div class="panel panel-default product-details">
        <div class="panel-body" style="">
            <div class="col-xs-5 nopadding">
                <div class="col-xs-2 nopadding">
                    <a href="javascript:void(0)" onclick="delete_posale('3074')">
                    <span class="fa-stack fa-sm productD">
                      <i class="fa fa-circle fa-stack-2x delete-product"></i>
                      <i class="fa fa-times fa-stack-1x fa-fw fa-inverse"></i>
                    </span>
                    </a>
                </div>
                <div class="col-xs-10 nopadding">
                  <span class="textPD">CMS Developmet</span>
                </div>
            </div>
            <div class="col-xs-2">
              <span class="textPD">550.00</span>
            </div>
            <div class="col-xs-3 nopadding productNum">
              <a href="javascript:void(0)">
                <span class="fa-stack fa-sm decbutton">
                  <i class="fa fa-square fa-stack-2x light-grey"></i>
                  <i class="fa fa-minus fa-stack-1x fa-inverse white"></i>
                </span>
              </a>
              <input id="qt-3074" onchange="edit_posale(3074)" class="form-control" value="7" placeholder="0" maxlength="2" type="text">
              <a href="javascript:void(0)">
              <span class="fa-stack fa-sm incbutton">
                <i class="fa fa-square fa-stack-2x light-grey"></i>
                <i class="fa fa-plus fa-stack-1x fa-inverse white"></i>
              </span>
              </a>
            </div>
            <div class="col-xs-2 nopadding ">
              <span class="subtotal textPD">3850.00  GBP</span>
            </div>
        </div>
    </div>
</div>

         </div>
         <div class="footer-section">
            <div class="table-responsive col-sm-12 totalTab">
               <table class="table">
                  <tr>
                     <td class="active" width="40%">Subtotal</td>
                     <td class="whiteBg" width="60%"><span id="Subtot"></span>
                        <span class="float-right"><b id="ItemsNum"><span></span> Item</b></span>
                     </td>
                  </tr>
                  <tr>
                     <td class="active">TAX</td>
                     <td class="whiteBg"><input type="text" value="" onchange="total_change()" id="" class="total-input TAX" placeholder="N/A"  maxlength="5">
                        <span class="float-right"><b id="taxValue"></b></span>
                     </td>
                  </tr>
                  <tr>
                     <td class="active">Discount</td>
                     <td class="whiteBg"><input type="text" value="" onchange="total_change()" id="" class="total-input Remise" placeholder="N/A"  maxlength="5">
                        <span class="float-right"><b id="RemiseValue"></b></span>
                     </td>
                  </tr>
                  <tr>
                     <td class="active">Total</td>
                     <td class="whiteBg light-blue text-bold"><span id="total"></span></td>
                  </tr>
               </table>
            </div>
            <button type="button" onclick="cancelPOS()" class="btn btn-red col-md-6 flat-box-btn"><h5 class="text-bold">Cancel</h5></button>
            <button type="button" class="btn btn-green col-md-6 flat-box-btn" data-toggle="modal" data-target="#AddSale"><h5 class="text-bold">Payment</h5></button>
         </div>

      </div>
      <div class="col-md-7 right-side nopadding">
              <div class="row row-horizon">
                  <span class="categories selectedGat" id=""><i class="fa fa-home"></i></span>

              </div>
              <div class="col-sm-12">
                 <div id="searchContaner">
                     <div class="input-group stylish-input-group">
                         <input type="text" id="searchProd" class="form-control"  placeholder="Search" >
                         <span class="input-group-addon">
                             <button type="submit">
                                 <span class="glyphicon glyphicon-search"></span>
                             </button>
                         </span>
                     </div>
                </div>
              </div>
              <!-- product list section -->
         <div id="productList2">
            <div class="col-sm-2 col-xs-4" style="display: block;">
              <a href="javascript:void(0)" class="addPct" id="product-6281086000533" onclick="add_posale('39')">
                <div class="product color03 flat-box waves-effect waves-block">
                  <h3 id="proname">Computer</h3>
                  <input id="idname-39" name="name" value="Computer" type="hidden">
                  <input id="idprice-39" name="price" value="350" type="hidden">
                  <input id="category" name="category" value="computers" type="hidden">
                  <div class="mask">
                    <h3>350.00 GBP</h3>
                    <p>computer desktops, Wi-Fi, webcam, screen,…</p>
                  </div>
                  <img src="http://www.dar-elweb.com/demos/zarpos/files/products/08153b71766514c0d83ea3ea97de00f2_thumb.jpg" alt="Computer">
                </div>
              </a>
             </div>

         </div>
      </div>
   </div>
</div>
<!-- /.container -->
