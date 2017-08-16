<script type="text/javascript">

setNavElemActive(["userDropdown", "navBotCI"]);

// string
// contains the path to the botCommands folder (NOT TO ANY JSON FILES!)
var botCommandsFolderURL = "<?php echo base_url('assets/json/botCommands/') ?>";
// string
// contains the path to the current website
var botCIPHPURL = "<?php echo site_url('botCI/'); ?>";
// string
// contains the current language of the website
var language = "de";
// string
// stores the username of the client (get's it in document.ready through php from the current session)
var username = "<?php echo $_SESSION['uname'];?>";

</script>

<!-- files -->
<link href="<?php echo base_url('assets/css/botCI.css') ?>" type="text/css" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/botCI.js') ?>" type="text/javascript"></script>

    <!-- body html -->
    <div class="container">

    <!-- confirm reset modal -->
            <div class="modal fade" id="confirmResetModal" tabindex="-1" role="dialog" aria-labelledby="confirmResetModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addIPLabel">Reset all bot commands</h4>
                        </div>
                        <div class="modal-body">
                            <h4>Warnung!</h4>
                            <p>Willst du wirklich alle Antworten der Bots zurücksetzen?</p>
                            <p>Tippe "<span class="text-danger"><b>RESET</b></span>" und clicke den Reset button um die JSON Datei zurückzusetzen.</p>
                            <div class="form-group row">
                            <div class="col-xs-9 col-sm-7 col-md-5 col-lg-3">
                                <input type="text" autocomplete="off" class="form-control input-sm" id="resetInput" placeholder="RESET" value="" onkeyup="checkResetInput(this.value)">
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" id="resetButton" disabled onclick="resetBotCommands()">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
    <!-- End of confirm reset modal -->

    <!-- confirm delete command modal -->
            <div class="modal fade" id="confirmDeleteCommandModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCommandModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addIPLabel">Delete <span id="commandToDeleteName"></span> command</h4>
                        </div>
                        <div class="modal-body">
                            <h4>Warnung!</h4>
                            <p>Willst du wirklich diesen command komplett löschen?</p>
                            <p>Wenn du diesen command löscht, kann das nicht mehr rückgängig gemacht werden.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" id="deleteCommand" onclick="deleteCommand()">Delete command</button>
                        </div>
                    </div>
                </div>
            </div>
    <!-- End of confirm delete command modal -->

    <!-- uncommited changes modal -->
            <div class="modal fade" id="uncommitedChangesModal" tabindex="-1" role="dialog" aria-labelledby="uncommitedChangesModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addIPLabel">Uncommited Changes</h4>
                        </div>
                        <div class="modal-body">
                            <h4>Warnung!</h4>
                            <p>Du hast deine Änderungen für den offenen command noch nicht commitet.</p>
                            <p>Wenn du fortfährst werden deine Änderungen zurückgesetzt.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="discardChangesButton" class="btn btn-danger" data-dismiss="modal" onclick="updateJumbotron()">Discard Changes</button>
                            <button type="button" class="btn btn-primary" id="resetButtonChanges" onclick="changeSelect2Back()" data-dismiss="modal">Let me check</button>
                        </div>
                    </div>
                </div>
            </div>
    <!-- End of uncommited changes modal -->

    <!-- newCommand modal -->
            <div class="modal fade" id="newCommandNameModal" tabindex="-1" role="dialog" aria-labelledby="newCommandNameModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addIPLabel">Add new bot Command</h4>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group row">
                                    <div class="col-xs-10 col-sm-10 col-md-5 col-lg-5" style="margin-left: 25px;">
                                        <input type="text" class="form-control form-control-sm" onkeyup="checkNewCommandName(this)" id="newCommandName" aria-describedby="newCommandName" placeholder="Enter name">
                                        <small id="newCommandNameHelp" class="form-text text-muted">Please name the command without any prefixes and spaces.</small>
                                    </div>
                                    <div class="col-xs-10 col-sm-10 col-md-5 col-lg-5">
                                            <div class="inputFeedbackDanger" id="commandNameFeedback" style="font-size: 17px;"></div>
                                    </div>
                                </div>
                            </form>
                            <div>
                                <h4>
                                    <span style="margin-right: 10px;">Command answers:</span>
                                    <button class="btn btn-success btn-xs" onclick="addAnswer()">
                                        <span class="fa fa-plus" aria-hidden="true"></span>
                                    </button>
                                </h4>
                                <div id="newCommandsAnswersContainer">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="commitNewCommandButton" onclick="commitNewCommandButton()">Commit new command</button>
                        </div>
                    </div>
                </div>
            </div>
    <!-- End of newCommand modal -->

    <!-- new variable modal -->
            <div class="modal fade" id="addVariable" tabindex="-1" role="dialog" aria-labelledby="addVariableLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addIPLabel">New variable</h4>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="newVariableIdentifier">Identifier</label>
                                    <input type="text" onkeyup="checkVariableIdentifier(this)" class="form-control form-control-sm dangerInput" id="newVariableIdentifier" aria-describedby="newVariableIdentifier" placeholder="Enter identifier">
                                    <div class="varIdentifierFeedback" id="variableIdentifierFeedback">Der Identifier () darf nicht leer sein und keine Leerzeichen enthalten!</div>
                                </div>
                                <div class="form-group">
                                    <label for="newVariableDescription">Description</label>
                                    <input type="text" class="form-control form-control-sm" id="newVariableDescription" aria-describedby="newVariableDescription" placeholder="Enter description">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="addVarButton" onclick="addVariable()" disabled>Add variable</button>
                        </div>
                    </div>
                </div>
            </div>
    <!-- End of new variable modal -->

    <!-- delete variable modal -->
            <div class="modal fade" id="deleteVariable" tabindex="-1" role="dialog" aria-labelledby="deleteVariableLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addIPLabel">Delete variable</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-hover">
                                <tbody id="deleteVariableContainer">
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
    <!-- End of delete variable modal -->

    <div class="form-inline">
            <div style="margin-right: 15px;" class="form-group col-md-2">
                <select id="searchForCommandSelect" style="width: 100%;"></select>
            </div>
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#newCommandNameModal" style="margin-right: 5px; margin-bottom: 10px; vertical-align: top;">Add new command</button>
            <button class="pull-right btn btn-danger" type="button" data-toggle="modal" data-target="#confirmResetModal" style="margin-bottom: 10px; vertical-align: top;">Reset commands</button>
    </div>

        <div id="mirFaelltGeradeKeinNameEin">
            <div id="messageContainer"></div>
            <div id="jumbotronContainer"></div>
        </div>

    </div>