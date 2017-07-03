<?php $this->layout('main', ['title' => 'Votre passeport']) ?>
<div class="content-section-a">

  <div class="container">
    <div class="row">
     <div class="col-sm-8 col-sm-offset-2 panel-group ">
  		<div class="panel panel-default">

          <div class="btn-group " role="group" >
               <a class="btn btn-primary" href="index.php">
                    <span class="glyphicon glyphicon-step-backward"></span>
                    Revenir au questionnaire
               </a>
          </div>

        	<div class="panel-heading" >
            	<h4 class="panel-title">Votre passeport</h4>
          </div>
            <div class="panel-body">
              <p>
                Merci pour votre participation.
              </p>
              <p>
                Vous trouverez, ci-dessous, votre <b>passeport transition</b>. 
                Il résume votre engagement actuel dans la transition.<br/>
                Nous vous invitons à <b>le personnaliser</b> avec votre nom et une photo, et le partager sur les réseaux sociaux.
              </p>

              <hr />    

                        <img src="img/out/<?= $user->id ?>.png?<?= $user->last_update ?>" class="img-responsive" />

               <hr/>         

              <form method="post" enctype='multipart/form-data' action="?" >
              <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                      <label for="firstname" class="control-label">Prénom / pseudo</label><br/>
                      <input type="text" value="<?= $user->firstname ?>" id="firstname" name="firstname" placeholder="Prénom">
                    </div>
                    <div class="form-group">
                      <label for="name" class="control-label">Nom</label> (Facultatif)<br/>
                      <input type="text" value="<?= $user->name ?>" id="name" name="name" placeholder="Nom">
                    </div>
                    <div class="form-group">
                      <label for="email" class="control-label">Email</label> (Facultatif)

                      <?php $this->insert('partials/button-modal', [
                                      id => 'confidentialite', 
                                      title => 'Confidentialité', 
                                      text => "<p>
                                          Cet outil est une initiative à but <b>non lucratif</b> :  
                                          Les informations confidentielles (email, réponses, compte facebook) ne sont <b>ni communiquées ni vendues</b> à aucun tiers.     
                                        </p>
                                        <p>   
                                          L'email, falcultatif, nous permet de vous tenir au courant de l'évolution du <i>Passeport Transition</i> et de faire le suivi de vos engagements.
                                        </p>"]); ?>
                    <br/>


                      <input type="email" value="<?= $user->email ?>" id="email" name="email" placeholder="Email">
                    </div>

                    <div class="fileinput fileinput-new" data-provides="fileinput">
                      <label for="photo" class="control-label">Photo</label> (Facultatif)<br/> 
                      <input type="file" name="photo" accept=".jpg,.png,.jpeg" value="Choisissez un fichier">
                    </div>

                </div>

                <div class="col-sm-6">
                    <a class="btn btn-primary btn-lg" href="<?= $fb_url ?>"> 
               
                      <i class="fa fa-download fa-inverse"></i>
                      <i class="fa fa-facebook fa-inverse"></i>
               
                      Importer depuis Facebook
                    </a>
                </div>

                </div>

                <div class="row">
                  <hr/>
                  <div class="col text-center">
                  <div class="btn-group " role="group" >
                      <button type="submit" class="btn btn-success" href="#"> 
                          <i class="fa fa-refresh fa-inverse"></i> Rafraîchir le passeport
                       </button>
                  </div>
                  </div>
                </div>
                </form>
              <hr/>



               <h4> Partager </h4>

               <?php 
                  $public_link = "http://passeport-transition.fr/?userid=$user->id"; 
                  $private_link = "$public_link&secret=$user->secret";
                  $APP_ID = FB_APP_ID;
                  $public_link_v = urlencode("$public_link&v=$user->last_update");
                  $fb_link = "https://www.facebook.com/dialog/share?app_id=$APP_ID&display=popup&href=$public_link_v"; 
               ?> 


                <div class="panel panel-success">
                  <div class="panel-heading">Lien public</div>
                  <div class="panel-body">

                    <a class="btn btn-primary btn-lg" href="<?= $fb_link ?>" target="popup"> 
               
                      <i class="glyphicon glyphicon-share"></i>
                      <i class="fa fa-facebook fa-inverse"></i>
               
                      Partager sur Facebook
                    </a>

                    <br/><br/>

                    <p>
                        Vous pouvez partager ce passeport avec le lien public suivant :<br>
                        <a href="<?= $public_link ?>"><?= $public_link ?></a>
                    </p>                    
                 

                  </div>
                </div>

                <div class="panel panel-danger">
                  <div class="panel-heading">Lien privé</div>
                  <div class="panel-body">
                  Pour revenir ultérieurement et mettre à jour vos engagements et votre passeport, 
                  enregistrez ce lien (mais ne le partagez pas)<br>
                  <a href="<?= $private_link ?>"><?= $private_link ?></a>
                </div>
               </div>



            </div>
          </div>
       </div>
    </div>
  </div>
</div>

