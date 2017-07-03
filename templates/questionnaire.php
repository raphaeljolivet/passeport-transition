  <?php 

  if ($user) {
    $title = "Passeport Transition de $user->firstname $user->name";
    $description = 
      "$user->firstname $user->name a fait le point sur ses engagements.\n 
      Découvrez vous aussi des actions simples et concrètes pour participer au changement de société.";
    $image = "img/out/$user->id.png?v=$user->last_update";
  } else {
    $title = "Mon Passeport Transition : Faites le point sur vos engagements personnels.";
    $description = 
      "Le passeport transition vous permet de faire le point sur votre implication dans les transitions (écologique, énergétique, sociale, alimentaire, ...) 
      et vous propose une liste d'actions concrètes, pour changer les choses à votre niveau.";
  }
  $this->layout('main', 
  [
    'title' =>  $title, 
    'description' => $description, 
    'image' => $image]) ?>


        <div id="nav">
          <div class="navbar navbar-static-top">
            <div class="container-fluid">
              <ul id="nav-root" class="nav navbar-nav navbar-horiz">
                <?php foreach($data->sections as $section) : ?>
                <li>
                   <a href="#section-<?= $section->id ?>" title="<?= $section->name ?>">
                      <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x text-warning" style="color:<?= $section->icon_color ?>"></i>
                        <i class="fa fa-<?= $section->icon_symbol ?> fa-stack-1x" ></i>
                      </span>
                      <span class="hidden-xs"><?= $section->name ?></span>
                    </a>
                </li>
                <?php endforeach ?>
                <li>
                  <a href="javascript:submit()" class="btn btn-success" style="padding:7px">
                    <span class="glyphicon glyphicon-ok" title="Envoyer"></span>
                    <span class="hidden-xs">Terminer</span>
                  </a>
                </li>

                 <li>
                   <a href="#disqus_thread" title="Commentaires">
                      <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x text-warning" style="color:#b1e9ff"></i>
                        <i class="fa fa-comments fa-stack-1x" ></i>
                      </span>
                      <span class="hidden-xs">Commentaires</span>
                    </a>
                </li>

              </ul>
            </div>
          </div>
        </div>   


        <div class="content-section-a">
          <div class="container">
            <div class="row">
              <div class="col-sm-8 col-sm-offset-3 texte-intro">
                  <h3>
                    <span class="fa-stack">
                        <i class="fa fa-circle fa-stack-2x text-warning" style="color:#fbff96"></i>
                        <i class="fa fa-question fa-stack-1x" ></i>
                    </span>  
                    Introduction
                  </h3>

                <?php if (($user != null) && $user->authenticated) : ?>
              
                  <p>
                  Bonjour <?= $user->firstname ?>
                  </p>

                  <P>
                  Il semble que vous ayez <b>déjà participé</b> au passeport transition.
                  </p>
                  
                  <p>
                  Vos réponses ont été pré remplies.<br/> 
                  Nous vous invitons à <b>faire le point</b> sur vos engagements.  
                  </p>

                  <a class="btn btn-primary" href="javascript:logout();">
                    <span class="glyphicon glyphicon-log-out"></span>
                    Se délogguer
                  </a>

                <?php else : ?>
                  <?php if ($user != null) : ?>

                  <p>
                    Bonjour,<br/>
                    <?= $user->firstname ?> a établi son passeport transition, et a partagé sa carte avec vous :
                    <img src="<?= $image ?>" class="img-responsive" />
                  </p>

                  <?php endif?>

      
                <p>
                  Le passeport transition vous permet de vous <b>engager</b> et de faire le point sur votre <b>implication</b> dans les transitions: écologique, 
                  énergétique, sociale, alimentaire, ...
                </p>
                <p>
                  Cette page présente une série de <b>gestes concrets</b> et <b>d'engagements simples</b> que vous pouvez prendre dès aujourd'hui, 
                  pour participer à votre échelle aux <b>transformations indispensables</b> à notre société.
                </p>
                <p>
                   Vos réponses à ce questionnaire produiront un <b><i>passeport transition</i></b> personnalisé :<br/> 
                   une photographie de <b>l'avancée de votre transition personnelle</b>, à conserver ou partager sur les réseaux sociaux. 
                </p>
                <p>C'est parti!</p>

                <?php endif ?> 
              </div>
            </div>

            <div class="row">
              <form id="form" method="post" action="passeport.php">

             <!-- Navigation -->

            

              <?php foreach($data->sections as $section) : ?>
                <div class="col-sm-8 col-sm-offset-3 panel-group" id="accordion">
                  <div class="panel panel-default">
                    <div id="section-<?= $section->id ?>" class="panel-heading" data-toggle="collapse" data-target="#collapse-<?= $section->id ?>">
                      <h4 class="panel-title">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-circle fa-stack-2x text-warning" style="color:<?= $section->icon_color ?>"></i>
                          <i class="fa fa-<?= $section->icon_symbol ?> fa-stack-1x" ></i>
                        </span>
                        <?= $section->name ?>
                      </h4>
                    </div>
                    <div id="collapse-<?= $section->id ?>" class="panel-collapse collapse in">
                      <div class="panel-body">
                        <div>
                          <?= $section->getDescription() ?>
                        </div>


                        <?php 
                        $question_num = 0;  
                        foreach($section->questions as $question ) : 
                        $question_num +=1;
                        $question_id = $section->id . '_' . $question->id ?>                        
                        <hr class="question-separator"/>
                        <div>
                          <h4>
                            <span class="title-num"><?= $question_num ?></span> 
                            <?= $question->name ?>
                          <?php $this->insert('partials/info-button', ['id' => $question_id . '_modal']); ?>
                          </h4>
                          <?php $this->insert('partials/modal', 
                              ['id'=>$question_id . '_modal', 
                              'title'=> $question->name, 
                              'text'=>$question->getDescription()]); ?>
                          
                          <div class="question-group" >
                            <?php foreach($data->options as $option) : 
                              $option_id = $question_id . '_' . $option->id ?>
                              <?php if (($option->id == 'plus') && (is_null($question->extra_option_label))) continue ?>
                              <div class="radio radio-<?= $option->color ?>">
                                <input 
                                  data-score="<?= $option->score ?>"
                                  data-score-color="<?= $option->color ?>"
                                  id="<?= $option_id ?>" 
                                  type="radio" 
                                  value="<?= $option->id ?>" 
                                  name="<?= $question_id ?>" 
                                  <?= ($question->response == $option->id) ? 'checked="checked"' : "" ?> >
                                <?php if ($option->id == 'plus')  : ?>
                                  <label for="<?= $option_id ?>">
                                    <b><?= $option->label ?> : </b> 
                                    <?= $question->extra_option_label ?> 
                                    <?php $this->insert('partials/button-modal', [
                                      'id'=>$question_id . '_extra', 
                                      'title'=> $question->extra_option_label, 
                                      'text'=>$question->getExtraOptionDescription()]); ?>
                                  </label>
                                <?php else : ?>
                                  <label for="<?= $option_id ?>"><?= $option->label ?></label>
                                <?php endif ?>
                              </div>
                            <?php endforeach ?>
                          </div>
                        </div>

                      <?php endforeach ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach ?>

              <div class="col-sm-8 panel-group col-sm-offset-3 text-center">
                 <a href="javascript:submit()" class="btn btn-success btn-lg" value="Terminer">Terminer</a>
              </div>
          </form>
        </div> <!-- Row -->

           <div class="row">
              <div class="col-sm-8 col-sm-offset-3 texte-intro">
                  <h3>
                    <span class="fa-stack">
                        <i class="fa fa-circle fa-stack-2x text-warning" style="color:#b1e9ff"></i>
                        <i class="fa fa-comments fa-stack-1x" ></i>
                    </span>  
                    Commentaires
                  </h3>
              
                  <p>
                    Si vous avez des commentaires, ou suggestions d'engagements, c'est ici :
                  </p>

                  <hr/>

                  <div id="disqus_thread"></div>
                  <script>
                    var disqus_config = function () {
                      this.page.url = "http://passeport-transition.fr/";
                      this.page.identifier = "home";
                    };
                    (function() { // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');
                    s.src = 'https://passeport-transition-fr.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                    })();
                  </script>
                  <noscript>
                    Merci d'activer le Javascript pour voir les  <a href="https://disqus.com/?ref_noscript">commentaires</a>
                  </noscript>
              </div>
            </div>

    </div>
    <!-- /.container -->

  </div>
  <!-- /.content-section-a -->


  <!-- Confirm modal -->
<div id="confirm-modal" class="modal"  role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Questionnaire incomplet</h4>
        </div>
        <div class="modal-body">
          <p>
            Il y a encore <span id="confirm-num">X</span> questions sans réponses.<br/>
            Voulez vous quand même continuer ?
          </p>
        </div>
        <div class="modal-footer">
            <button id="modal-submit" type="button" class="btn btn-success" data-dismiss="modal">Continuer</button>
            <button type="button" class="btn btn-error" data-dismiss="modal">Annuler</button>
        </div>
      </div>    
  </div>
</div>

<!-- Sore bubble -->
<div id="score" >
</div>