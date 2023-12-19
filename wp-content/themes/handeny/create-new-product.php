<?php
/*
Template Name: Add New Product
*/
?>
<?php get_header(); ?>
  <div id="primary" class="content-area">
    <main id="main" class="site-main">
      <form id="create_new_product">
        <div>
          <div><label>Product Name</label></div>
          <div><input type="text" name="productname" class="productname"/></div>
        </div>
        <div>
          <div><label>Product Price</label></div>
          <div><input type="text" name="price" class="price"/></div>
        </div>
        <div>
          <div><label>Product create date</label></div>
          <div><input type="date" name="_custom_product_date_field" class="_custom_product_date_field" /></div>
        </div>
        <div>
          <div><label>Select product type</label></div>
          <div>
            <select type="text" name="custom_product_select_field" class="custom_product_select_field">
              <option value="" selected="selected">Select...</option>
              <option value="Rare">Rare</option>
              <option value="Frequent">Frequent</option>
              <option value="Unusual">Unusual</option>
            </select>
          </div>
        </div>
        <div>
          <input type="file" id="user-file">
        </div>
        <div>
          <input name="submit" type="submit" value="Create new product" class="submit_create_product" />
        </div>
      </form>
    </main>
  </div>

<script>
  //Create new product in front page
  jQuery('#create_new_product').submit(function(){
      event.preventDefault();

      let link="<?php echo admin_url('admin-ajax.php')?>";
      let form = jQuery('#create_new_product').serialize();
      let formData = new FormData;

      formData.append('updoc', document.querySelector('input[type=file]').files[0]);
      formData.append('action','create_product');
      formData.append('create_product', form);
      jQuery('#submit').attr('disabled',true);
      jQuery.ajax({
          url:link,
          data:formData,
          processData:false,
          contentType:false,
          type:'post',
          success:function(result){
              jQuery('#submit').attr('disabled',false);
              jQuery('#create_new_product')[0].reset();
              alert('Product created!')
          }
      });
  });    
</script>

<?php
get_footer(); ?>