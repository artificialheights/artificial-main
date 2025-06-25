jQuery(($)=>{
 function init(btn,field,prev){
   let frame;
   $('#'+btn).on('click',e=>{
     e.preventDefault();
     if(frame){frame.open();return;}
     frame = wp.media({title:'Select or Upload',button:{text:'Use'},multiple:false});
     frame.on('select',()=>{const a=frame.state().get('selection').first().toJSON();
        $('#'+field).val(a.url); $(prev).attr('src',a.url).show();});
     frame.open();
   });
 }
 init('am-logo-upload','am-logo-url','#am-logo-preview');
 $('#am-logo-remove').on('click',e=>{e.preventDefault();$('#am-logo-url').val('');$('#am-logo-preview').hide();});
});