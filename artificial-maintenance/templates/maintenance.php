<?php if ( ! defined('ABSPATH') ) exit; $g=fn($k,$d='')=>get_option($k,$d); ?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo esc_html($g('artmaint_title','Site Under Maintenance')); ?></title>
<style>@import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600&display=swap');
body{margin:0;height:100%;background:#000;color:#fff;font-family:'Rajdhani', sans-serif}
.bg{position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2}
.wrap{display:flex;justify-content:center;align-items:center;min-height:100vh}
.box{background:rgba(17,17,17,.75);width:350px;max-width:350px;padding:1rem 1.5rem;border-radius:12px;text-align:center}
.box,.box *{color:#fff!important}
@keyframes glowCycle{0%,100%{text-shadow:0 0 6px var(--color-1);}25%{text-shadow:0 0 6px var(--color-2);}50%{text-shadow:0 0 6px var(--color-3);}75%{text-shadow:0 0 6px var(--color-4);}}
.glow{animation:glowCycle 6s infinite ease-in-out}

.toggle{width:48px;height:48px;display:flex;align-items:center;justify-content:center;background:transparent!important;border:2px solid #fff!important;border-radius:50%!important;font-size:1.75rem;color:#fff!important;cursor:pointer;margin:.75rem auto;padding:0}
.login{
  max-height:0;
  overflow:hidden;
  opacity:0;
  transform:translateY(-20px);
  transition:max-height .45s ease, opacity .45s ease, transform .45s ease;
}
.login.open{
  max-height:500px;
  opacity:1;
  transform:translateY(0);
}
.login form{display:flex;flex-direction:column;gap:.6rem;margin-top:.5rem;}

.login input{width:260px;max-width:90%;padding:.45rem .6rem;border:1px solid #666;border-radius:4px;background:#222;color:#fff;}
.login input::placeholder{color:#999;}
.login input[type=submit]{background:transparent!important;border:2px solid #fff;color:#fff;font-weight:600;cursor:pointer;padding:.45rem .6rem;transition:background .3s,color .3s}
.login input[type=submit]:hover{background:#fff;color:#000}
padding:.45rem .6rem;border:1px solid #666;border-radius:4px;background:#222;color:#fff;}
.login input[type=submit]{background:transparent!important;border:2px solid #fff;color:#fff;font-weight:600;cursor:pointer;padding:.45rem .6rem;transition:background .3s,color .3s}

</style>
<?php wp_head();?></head><body>
<?php switch($g('artmaint_bg_type','none')){
 case 'image': echo '<div class="bg" style="background:url('.esc_url($g('artmaint_bg_img')).') center/cover no-repeat"></div>'; break;
 case 'video': echo '<video class="bg vid" preload="none" muted loop playsinline style="opacity:0;transition:opacity .6s;"><source src="'.esc_url($g('artmaint_bg_vid')).'" type="video/mp4"></video>'; break;
 case 'youtube':
   if(preg_match('#(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:.*[?&]v=|(?:v|embed)/))([\w-]{11})#i',$g('artmaint_bg_yt'),$m)){
     $id=$m[1];
     echo '<iframe class="bg" src="https://www.youtube.com/embed/'.$id.'?autoplay=1&mute=1&loop=1&playlist='.$id.'&controls=0&modestbranding=1&rel=0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
   } break;
}?>
<div class="wrap"><div class="box">
<?php if($g('artmaint_html')) echo '<div class="custom">'.$g('artmaint_html').'</div>';?>
<?php if($g('artmaint_logo')) echo '<img src="'.esc_url($g('artmaint_logo')).'" alt="Logo" style="max-width:160px;margin:.5rem auto">';?>
<h1 class="<?php echo get_option('artmaint_title_glow')?'glow':'';?>"><?php echo esc_html($g('artmaint_title','Site Under Maintenance'));?></h1>
<p class="desc"><?php echo esc_html($g('artmaint_desc',"We'll be back shortly."));?></p>
<?php if(get_option('artmaint_show_login')): ?>
<?php if( get_option('artmaint_show_login') ): ?>
<button id="am-lock" class="toggle">🔒</button>
<div id="login-container" class="login"></div>
<?php endif; ?>
<script type="text/template" id="am-login-tpl"><?php wp_login_form(['echo'=>true,'remember'=>false]);?></script>
<?php endif; ?>

</div></div>
<?php wp_footer();?>
<?php if(get_option('artmaint_show_login')): ?>
<script>
document.addEventListener('DOMContentLoaded',()=>{
  const btn=document.getElementById('am-lock'); if(!btn) return;
  const login=document.querySelector('.login');
  const tpl=document.getElementById('am-login-tpl').textContent.trim();
  btn.addEventListener('click',()=>{
     
if(!login.innerHTML){
   login.innerHTML=tpl;
   const u=document.getElementById('user_login'); if(u) u.placeholder='Username or Email';
   const p=document.getElementById('user_pass');  if(p) p.placeholder='Password';
}

     login.style.display='block';
     login.classList.toggle('open');
  });
});
</script>
<?php endif; ?>


<script>
document.addEventListener('DOMContentLoaded',()=>{
 const v=document.querySelector('.vid');
 if(v){
   if(v.dataset.loaded!=='1'){
     v.load();
     v.addEventListener('canplay',()=>{v.style.opacity='1';});
     v.dataset.loaded='1';
   }
 }
});
</script>

<?php if( get_option('artmaint_bg_type')==='video' ): ?>
<script>
document.addEventListener('DOMContentLoaded',()=>{
 const poster = "<?php echo esc_js( get_option('artmaint_bg_poster') ); ?>";
 const lowUrl = "<?php echo esc_js( get_option('artmaint_bg_vid_low') ); ?>";
 const hdUrl  = "<?php echo esc_js( get_option('artmaint_bg_vid') ); ?>";
 const body = document.body;
 // create low-res after 300ms to ensure first paint
 setTimeout(()=>{
   if(!lowUrl){ return; }
   const low = document.createElement('video');
   low.className='bg vid-low';
   low.muted=true; low.loop=true; low.playsInline=true;
   low.preload='auto';
   low.poster=poster;
   low.style.cssText='position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2;opacity:0;transition:opacity .6s';
   low.innerHTML=`<source src="${lowUrl}" type="video/mp4">`;
   body.appendChild(low);
   low.addEventListener('canplay',()=>{
      low.style.opacity='1';
      if(hdUrl){
         const hd=document.createElement('video');
         hd.className='bg vid-hd';
         hd.muted=true; hd.loop=true; hd.playsInline=true; hd.preload='none';
         hd.style.cssText=low.style.cssText;
         hd.innerHTML=`<source src="${hdUrl}" type="video/mp4">`;
         body.appendChild(hd);
         hd.load();
         hd.addEventListener('canplay',()=>{hd.style.opacity='1'; low.remove();});
      }
   });
   low.load();
 },300);
});
</script>
<?php endif; ?>

</body></html>