  <?php $this->layout('main', ['title' => 'Questionnaire']) ?>

        <div class="content-section-a">

          <div class="container">
            <div class="row">
              <div class="col-sm-8 col-sm-offset-2 texte-intro">
                  <h3>Introduction</h3>
                <?php if (($user != null) && $user->authenticated) : ?>
              
                  <p>
                  Bonjour <?= $user->firstname ?>
                  </p>

                  <P>
                  Il semble que vous ayez <b>déjà participé</b> 
                  au passeport transition.
                  </p>
                  
                  <p>
                  Vos réponses ont été pré remplies.<br/> 
                  Nous vous invitons à <b>faire le point</b> sur vos engagements.  
                  </p>

                <?php else : ?>
                  <?php if ($user != null) : ?>

                  <p>
                    Bonjour,<br/>
                    <?= $user->firstname ?> a établi son passeport transition, et a partagé sa carte avec vous :
                    <img src="img/out/<?= $user->id ?>.png?<?= $user->last_update ?>" class="img-responsive" />
                  </p>

                  <?php endif?>

      
                <p>
                  Le passeport transition vous permet de vous <b>engager</b> et de faire le point votre <b>implication</b> dans les transitions: écologique, 
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
                <div class="col-sm-8 col-sm-offset-2 panel-group" id="accordion">
                  <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-target="#collapse-<?= $section->id ?>">
                      <h4 class="panel-title">
                        <span class="fa-stack ">
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

                        <?php foreach($section->questions as $question ) : 
                        $question_id = $section->id . '_' . $question->id ?>                        
                        <hr class="question-separator"/>
                        <div>
                          <h4>
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

              <div class="col-sm-8 panel-group col-sm-offset-2 text-center">
                 <input form="form" type="submit" class="btn btn-success btn-lg" value="Terminer" />
              </div>


          </form>
        </div>

    </div>
    <!-- /.container -->

  </div>
  <!-- /.content-section-a -->