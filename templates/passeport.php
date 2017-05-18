<?php $this->layout('main', ['title' => 'Votre passeport']) ?>
<div class="content-section-a">

  <div class="container">
    <div class="row">
     <div class="col-sm-8 col-sm-offset-2 panel-group " id="accordion">
  		<div class="panel panel-default">
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
              <p>
                Cet outil est une initiative à but <b>non lucratif</b> :  
                Les informations confidentielles (email, réponses, compte facebook) ne sont <b>ni communiquées ni vendues</b> à aucun tiers.     
              </p>
              <p>   
                L'email, falcultatif, nous permet de vous tenir au courant de l'évolution du <i>Passeport Transition</i> et de faire le suivi de vos engagements.
              </p>
          
              <hr />             

              <form method="post" enctype='multipart/form-data' action="?" >
              <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                      <label for="firstname" class="control-label">Prénom</label><br/>
                      <input type="text" value="<?= $user->firstname ?>" id="firstname" name="firstname" placeholder="Prénom">
                    </div>
                    <div class="form-group">
                      <label for="name" class="control-label">Nom</label><br/>
                      <input type="text" value="<?= $user->name ?>" id="name" name="name" placeholder="Nom">
                    </div>
                    <div class="form-group">
                      <label for="email" class="control-label">Email</label> (Facultatif)<br/>
                      <input type="email" value="<?= $user->email ?>" id="email" name="email" placeholder="Email">
                    </div>

                    <div class="fileinput fileinput-new" data-provides="fileinput">
                      <label for="photo" class="control-label">Photo</label> (Facultatif)<br/> 
                      <input type="file" name="photo" accept="image/*" value="Choisissez un fichier">
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
                       <a class="btn btn-primary" href="index.php">
                            <span class="glyphicon glyphicon-step-backward"></span>
                            Revenir au questionnaire
                       </a>
                  </div>
                  </div>
                </div>
                </form>
              <hr/>

              <img src="img/out/<?= $user->id ?>.png?<?= $user->last_update ?>" class="img-responsive" />

            </div>
          </div>
       </div>
    </div>
  </div>
</div>

