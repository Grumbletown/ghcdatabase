/**
 * @author Jonas Fritsch
 * @file Stores almost all the functionality for the bot control interface (botCI) to work.
 * @version 1.1.1.0
 * ----------------------------------------------------------------------------------------
 * LAST CHANGES on 22.08.2017:
 *  -   changed 'dataType: "html"' in ajax calls
 */


"use strict";


// Object
// the object that stores the commands
var botCommandObject;
// Object
// the object that stores the backup commands
var botCommandObjectBackup;
// string
// the currently open command name (formatted to use it in botCommandObject like addIP)
var openCommand;
// bool
// is the open command in edit mode or not
var inEditMode = false;
// DOM-Element
// stores the add Variable button that was last clicked
var lastClickedAddVariableBtn;
// DOM-Element
// stores the variables container that was last opened
var lastOpenedVarContainer;
// bool
// is a command open
var commandIsOpen = false;
// bool
// is the new command modal open
var newCommandIsOpen = false;
// bool
// does the open command that is in edit mode have any errors
var editModeCommandErrors = false;
// bool
// does the open command that is in edit mode have any changes
var editModeCommandNew = false;





/**
 * This function gets called immediately when the page has finished loading
 * 1. Activate 'select2' for <select> element and adds 'onChange' Event Listener
 * 2. Fill the botCommandObject with the data from the botCommands.json file and fills the <select> element
 */
$(document).ready(function() {
    var searchForCommandSelect = $("#searchForCommandSelect");
    searchForCommandSelect.select2({
        placeholder: 'Select a command'
    }).on("change", function(event, change = true) { //'onChange' Event Listener of the <select> element.
        if (change) {
            //checks if the openBotCommand has any changes
            if (commandHasChanges()) {
                //changes --> open modal
                $('#uncommittedChangesModal').modal('show');
                $('#discardChangesButton').attr("onclick", "showJumbotron(searchForCommandSelect.val())");
                $('#resetButtonChanges').attr("onclick", 'searchForCommandSelect.val(openCommand).trigger("change")');
            } else {
                //no changes --> open new command
                showJumbotron(searchForCommandSelect.val());
            }
        }
    });

    $("#newCommandNameModal").on("shown.bs.modal", function() {
        newCommandIsOpen = true;
        checkNewCommandName(document.getElementById("newCommandName"));
    }).on("hidden.bs.modal", function() {
        newCommandIsOpen = false;
    });
    //modal z-order
    $(document).on('show.bs.modal', '.modal', function() {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });
    //modal scrollbar fix
    $(document).on('hidden.bs.modal', '.modal', function() {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

    $.getJSON(botCommandsFolderURL + language + "/botCommands.json").then(function(data) {
        botCommandObject = data;
        fillSelect();
    });

    $.getJSON(botCommandsFolderURL + language + "/botCommandsBackup.json").then(function(data) {
        botCommandObjectBackup = data;
    });
});

/**
 * Checks if any answer inside the bot command has changed
 * @returns {boolean} true if it has changed Answers
 * @returns {boolean} false if no answers of this botCommand were changed
 */
function commandHasChanges() {
    if (commandIsOpen) {
        return !document.getElementById("commitChanges").disabled;
    } else {
        return false;
    }
}

/**
 * Checks if the answer of the @param name answer has changed
 * (Is the newOutput different then the oldOutput of that answer)
 */
function answerHasChanges(name) {
    //check if new answer is something other then the old answer
    var toggleAnswerButton = $("#toggle" + name + "button");
    var answer = $("#" + name + "Answer");
    var newOutput = $("#newOutput" + name);
    var oldOutput = $("#oldOutput" + name);

    if (newOutput.val().localeCompare(oldOutput.val()) !== 0) {
        //Yes --> replace class 'unchanged' with 'changed'
        toggleAnswerButton.addClass('changed').removeClass('unchanged');
        answer.addClass('changed').removeClass('unchanged');
    } else {
        //No --> replace class 'changed' with 'unchanged'
        toggleAnswerButton.addClass('unchanged').removeClass('changed');
        answer.addClass('unchanged').removeClass('changed');
    }

    document.getElementById("commitChanges").disabled = !$(".changed")[0];
}

/**
 * Adds an 'onKeyUp' Event Listener to every newOutput <textarea> element
 * If the Listener gets triggered it executes the answerHasChanges function
 */
function addNewOutputListener(name) {
    $('#newOutput' + name).on('keyup', function() {
        answerHasChanges(name);
    });
}

/**
 * Checks if there are any errors
 * If not the button with @param id property disabled = false
 * Else button property disabled = true
 */
function setCommitButton(newCommandCommit) {
    if (newCommandCommit) {
        $("#commitNewCommandButton").prop("disabled", !($(".newCommandError").length === 0));
    } else {
        editModeCommandErrors = !($(".openCommandError").length === 0);
        setCommitChangesBtnEditMode();
    }
}

/**
 * Checks the current name of the new command
 */
function checkNewCommandName(element) {
    var unique = true;
    var name = element.value;
    var foundError = false;
    var feedback = $("#commandNameFeedback");

    if (name.indexOf(" ") >= 0 || name === "") {
        feedback.text("Der Name (" + name + ") darf nicht leer sein und keine Leerzeichen enthalten!");
        if (!$(element).hasClass("dangerInput")) {
            $(element).addClass("dangerInput");
        }
        foundError = true;

        if (!$(element).hasClass("newCommandError")) {
            $(element).addClass("newCommandError");
        }

        setCommitButton(true);
        return;
    }

    for (var i = 0; i < Object.keys(botCommandObject).length; i++) {
        if (Object.keys(botCommandObject)[i] === name) {
            unique = false;
            foundError = true;
        }
    }

    if (unique) {
        feedback.text("");
        if ($(element).hasClass("dangerInput")) {
            $(element).removeClass("dangerInput");
        }
    } else {
        feedback.text("Der command '" + name + "' exsistiert bereits!");
        if (!$(element).hasClass("dangerInput")) {
            $(element).addClass("dangerInput");
        }
    }

    if (foundError) {
        if (!$(element).hasClass("newCommandError")) {
            $(element).addClass("newCommandError");
        }
    } else {
        if ($(element).hasClass("newCommandError")) {
            $(element).removeClass("newCommandError");
        }
    }

    setCommitButton(true);
}

/**
 * Checks the current answer name of @param answer
 */
function checkNewAnswerNameJumbotron(answer) {
    var unique = true;
    var name = answer.value;
    var foundError = false;

    if (name.indexOf(" ") >= 0 || name === "") {
        if (!$(answer).hasClass("dangerInput")) {
            $(answer).addClass("dangerInput");
        }

        $(answer).closest("div").next("div").children("div").eq(0).text("Der Name (" + name + ") darf nicht leer sein und keine Leerzeichen enthalten!");
        foundError = true;

        if (newCommandIsOpen) {
            if (!$(answer).hasClass("newCommandError")) {
                $(answer).addClass("newCommandError");
            }
            setCommitButton(true);
        } else {
            if (!$(answer).hasClass("openCommandError")) {
                $(answer).addClass("openCommandError");
            }
            checkCommandInEditMode();
            setCommitButton(false);
        }

        return;
    }

    if (!newCommandIsOpen) {
        var commandAnswer = $(".commandAnswer");
        for (var b = 0; b < commandAnswer.length; b++) {
            if (commandAnswer[b].value === name && answer !== commandAnswer[b]) {
                unique = false;
            }
        }
    } else {
        var answerNames = $(".newAnswerName");
        for (var i = 0; i < answerNames.length; i++) {
            if (answerNames[i].value === name && answer !== answerNames[i]) {
                unique = false;
                foundError = true;
            }
        }
    }

    if (unique) {
        $(answer).closest("div").next("div").children("div").eq(0).text("");
        if ($(answer).hasClass("dangerInput")) {
            $(answer).removeClass("dangerInput");
        }
    } else {
        if (!$(answer).hasClass("dangerInput")) {
            $(answer).addClass("dangerInput");
        }
        $(answer).closest("div").next("div").children("div").eq(0).text("Die answer '" + name + "' exsistiert bereits!");
    }

    if (newCommandIsOpen) {
        if (foundError) {
            if (!$(answer).hasClass("newCommandError")) {
                $(answer).addClass("newCommandError");
            }
        } else {
            if ($(answer).hasClass("newCommandError")) {
                $(answer).removeClass("newCommandError");
            }
        }
    } else {
        if (foundError) {
            if (!$(answer).hasClass("openCommandError")) {
                $(answer).addClass("openCommandError");
            }
        } else {
            if ($(answer).hasClass("openCommandError")) {
                $(answer).removeClass("openCommandError");
            }
        }
    }

    if (newCommandIsOpen) {
        setCommitButton(true);
    } else {
        checkCommandInEditMode();
        setCommitButton(false);
    }
}

/**
 * Shows the jumbotron of the @param command and fills it with the data of the botCommandObject
 */
function showJumbotron(command) {
    commandIsOpen = true;
    openCommand = command;

    createJumbotron(command);

    //build UI based on selected command
    //adds for each object inside the command object an answer div in the answerContainer
    //with other toggleable content (new answer textarea, old answer textarea, available variables)
    var container = $("#answersContainer");
    for (var i = 0; i < Object.keys(botCommandObject[command]).length; i++) {
        var commandName = Object.keys(botCommandObject[command])[i];
        var oldCommandAnswer = botCommandObject[command][commandName].answer;
        var lastChangedAnswer = botCommandObject[command][commandName].lastChangedAnswer;

        createAnswer(container, commandName, oldCommandAnswer, lastChangedAnswer);

        addNewOutputListener(commandName);

        var varsContainer = $("#variablesContainer" + commandName);

        for (var b = 0; b < Object.keys(botCommandObject[command][commandName].variables).length; b++) {
            var varName = Object.keys(botCommandObject[command][commandName].variables)[b];
            var varIdentifier = botCommandObject[command][commandName].variables[varName];

            createAvailableVariable(varsContainer, varIdentifier, varName);
        }
    }

    $("#toggleStyleButton").click(function() {
        $("#styleInfo").slideToggle();
        $("#toggleStyleButton").toggleClass("closed open");
    });

    $('[data-toggle="tooltip"]').tooltip();
}

// noinspection JSUnusedGlobalSymbols
/**
 * Closes the uncommited changes and the new command modal
 */
function closeUCAndNCModals() {
    $('#uncommittedChangesModal').modal('hide');
    $("#newCommandNameModal").modal("hide");
}

// noinspection JSUnusedGlobalSymbols
/**
 * OnClick method for commit new command button
 */
function commitNewCommandButton() {
    var commandName = $('#newCommandName').val();

    if (commandName === "") {
        $("#commandNameFeedback").text("Der command muss einen Namen haben!");
        return;
    }

    if (commandHasChanges()) {
        //changes -> öffne modal
        $('#resetButtonChanges').attr("onclick", "closeUCAndNCModals()");
        $('#discardChangesButton').attr("onclick", "commitNewCommand()");
        $('#uncommittedChangesModal').modal('show');
    } else {
        commitNewCommand();
    }
}

/**
 * Commits a new bot command to the JSON file
 */
function commitNewCommand() {
    var commandName = $('#newCommandName').val();

    try {
        botCommandObject[commandName] = {};
        botCommandObjectBackup[commandName] = {};

        $(".newAnswerName").each(function(i, e) {
            var answerName = $(e).val();
            var answerDefaultOutput = $(e).closest(".row").next("div").children("div").eq(0).children("textarea").val();

            botCommandObject[commandName][answerName] = {};
            botCommandObject[commandName][answerName]["answer"] = answerDefaultOutput;
            botCommandObject[commandName][answerName]["variables"] = {};
            botCommandObject[commandName][answerName]["lastChangedAnswer"] = "Default Output";

            botCommandObjectBackup[commandName][answerName] = {};
            botCommandObjectBackup[commandName][answerName]["answer"] = answerDefaultOutput;
            botCommandObjectBackup[commandName][answerName]["variables"] = {};
            botCommandObjectBackup[commandName][answerName]["lastChangedAnswer"] = "Default Output";

            $(".availableVariable", $(e).closest(".row").next("div").children("div").eq(1)).each(function(i, e) {
                var variableName = e.innerHTML;
                var variableIdentifier = $(e).attr("data-original-title");

                botCommandObject[commandName][answerName]["variables"][variableName] = variableIdentifier;
                botCommandObjectBackup[commandName][answerName]["variables"][variableName] = variableIdentifier;
            });
        });

        document.getElementById("messageContainer").innerHTML =
            '<div class="alert alert-success alert-dismissable fade in">' +
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
            '<strong>You added the ' + commandName + ' command successfully!</strong>' +
            '</div>';
    } catch (e) {
        document.getElementById("messageContainer").innerHTML =
            '<div class="alert alert-danger alert-dismissable fade in">' +
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
            '<strong>Error! Please contact the coding stuff on the GHC Discord server and send him this:</strong>' +
            '<p class="mb-0">Exception: ' + e + " | Adding bot command: " + commandName + '</p>' +
            '</div>';
    }
    $('#newCommandNameModal').modal('hide');

    postBotCommandsObject();
    postBotCommandsObjectBackup();

    document.getElementById("jumbotronContainer").innerHTML = "";
    commandIsOpen = false;

    document.getElementById("searchForCommandSelect").innerHTML = "";

    fillSelect();
}

// noinspection JSUnusedGlobalSymbols
/**
 * Adds another answer field to the new command modal
 */
function addAnswer() {
    var answersContainer = $("#newCommandsAnswersContainer");

    answersContainer.prepend(
        '<span>' +
        '<div class="row">' +
        '<div class="input-group col-xs-10 col-sm-10 col-md-5 col-lg-5" style="margin-top: 5px; margin-bottom: 10px; margin-left: 35px;">' +
        '<span class="input-group-btn" style="border: 1px solid black; background-color: #e74c3c; padding-right: 5px; padding-left: 5px;">' +
        '<button class="btn btn-danger btn-xs" onclick="deleteAnswer(this)"><span class="fa fa-trash-o fa-2x" aria-hidden="true"></span></button>' +
        '</span>' +
        '<input type="text" class="form-control form-control-sm commandAnswer newAnswerName newCommandError" onkeyup="checkNewAnswerNameJumbotron(this)" id="newAnswerName" aria-describedby="newAnswerName" placeholder="Enter answer`s name">' +
        '<span class="input-group-btn" style="border: 1px solid black; margin-right: 15px;">' +
        '<button onclick="toggleDivNewAnswer(this)" class="btn btn-default expand closed" id="toggleAnswerButton" type="button" style="padding-right: 10px; border: 0px; background-position: center; padding-left: 50px;">&nbsp; </button>' +
        '</span>' +
        '</div>' +
        '<div class="col-xs-10 col-sm-10 col-md-5 col-lg-5" style="margin-top: 5px; margin-bottom: 10px;">' +
        '<div class="inputFeedbackDanger" id="answerNameFeedback">Der Name () darf nicht leer sein und keine Leerzeichen enthalten!</div>' +
        '</div>' +
        '</div>' +
        '<div class="answerContainer" style="display: none; margin-top: 5px; margin-bottom: 10px; margin-left: 35px;">' +
        '<div class="form-group">' +
        '<label for="defaultOutput">Default Output</label>' +
        '<textarea class="form-control" id="defaultOutput" rows="3" required></textarea>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="variable">' +
        '<span style="margin-right: 10px;">Available variables</span>' +
        '<button style="margin-right: 10px;" class="btn btn-success btn-xs" onclick="openAddVariableJumbotron(this)">' +
        '<span class="fa fa-plus" aria-hidden="true"></span>' +
        '</button>' +
        '<button class="btn btn-danger btn-xs" onclick="openDeleteVariableModal(this)">' +
        '<span class="fa fa-minus" aria-hidden="true"></span>' +
        '</button>' +
        '</label>' +
        '<div id="newVariablesContainer" class="varContainer">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</span>'
    );

    setCommitButton(true);
}

/**
 * @return {string} the UTC Time in format hours:minutes
 */
function getCurUTCTime(curDate) {
    return curDate.getUTCHours() + ":" + ((curDate.getUTCMinutes() < 10) ? "0" : "") + curDate.getUTCMinutes();
}

/**
 * @return {string} the UTC Date in format dd.mm.yyyy
 */
function getCurUTCDate(curDate) {
    return curDate.getUTCDate() + "." + (curDate.getUTCMonth() + 1) + "." + curDate.getUTCFullYear();
}

/**
 * Deletes an answer field
 */
function deleteAnswer(deleteButton) {
    $(deleteButton).closest("span").closest("div").closest("span").remove();

    if (newCommandIsOpen) {
        setCommitButton(true);
    } else {
        checkCommandInEditMode();
        setCommitButton(false);
    }
}

/**
 * Toggle next <div> element and change class for answer in new command modal
 */
function toggleDivNewAnswer(element) {
    $(element).closest("span").closest("div").closest(".row").next("div").slideToggle();
    $(element).toggleClass("closed open");
}

/**
 * Onclick function for editCommand button
 */
function editCommandButton() {
    if (commandHasChanges()) {
        //changes -> open modal
        $('#resetButtonChanges').attr("onclick", "$('#uncommittedChangesModal').modal('hide');");
        $('#discardChangesButton').attr("onclick", "editCommand()");
        $('#uncommittedChangesModal').modal('show');
    } else {
        editCommand();
    }
}

/**
 * Changes the mode of the jumbotron to an edit mode to edit the command
 */
function editCommand() {
    inEditMode = true;
    var commandAnswer = $(".commandAnswer");

    $("#commitChanges").prop('disabled', true);

    $(".deleteAnswerContainer").each(function(index) {
        $(this).css("display", "table-cell");
    });

    commandAnswer.each(function() {
        $(this).prop('disabled', false);
        $(this).addClass("oldAnswerEdit");
    });

    $(".commitChanges").each(function() {
        $(this).prop('disabled', false);
    });

    $(".variablesLabel").each(function() {
        $(this).html(
            '<span style="margin-right: 10px;">Available variables</span>' +
            '<button style="margin-right: 10px;" class="btn btn-success btn-xs" onclick="openAddVariableJumbotron(this)">' +
            '<span class="fa fa-plus" aria-hidden="true"></span>' +
            '</button>' +
            '<button class="btn btn-danger btn-xs" onclick="openDeleteVariableModal(this)">' +
            '<span class="fa fa-minus" aria-hidden="true"></span>' +
            '</button>'
        )
    });

    commandAnswer.each(function() {
        if ($(this).hasClass("changed")) {
            $(this).toggleClass("changed unchanged");
        }
    });

    $(".toggleAnswer").each(function() {
        if ($(this).hasClass("changed")) {
            $(this).toggleClass("changed unchanged");
        }
    });

    $(".answerInputFields").each(function() {
        $(this).css("display", "none");
    });

    $("#addAnswerToJumbotronBtn").css("display", "inline-block");

    $("#editCommandBtn").css("display", "none");
    $("#deleteCommandBtn").css("display", "inline-block");
}

/**
 * CURRENTLY UNUSED
 * Changes the mode of the jumbotron to an normal mode to exit the edit mode
 */
/*
function normalCommand() {
    inEditMode = false;

    //Change den 'Edit' Button
    var editBtn = $("#editCommandBtn");
    editBtn.attr("onclick", "editCommand()");
    editBtn.html(
        '<i class="fa fa-pencil" aria-hidden="true"></i>&nbsp; Edit'
    );

    $("#commitChanges").prop('disabled', true);

    $(".deleteAnswerContainer").each(function(index) {
        $(this).css("display", "none");
    })

    $(".commandAnswer").each(function(index) {
        $(this).prop('disabled', true);
        answerHasChanges($(this).val());
    })

    $(".commitChanges").each(function(index) {
        $(this).prop('disabled', true);
    })

    $(".variablesLabel").each(function(index) {
        $(this).html('Available variables');
    })

    $(".answerInputFields").each(function(index) {
        $(this).css("display", "block");
    })

    $("#addAnswerToJumbotronBtn").css("display", "none");

    $("#deleteCommandBtn").css("display", "none");
}*/

/**
 * Commits a changed bot command
 */
function changeBotCommand() {
    if (!inEditMode) {
        try {
            var headerValue = document.getElementById("headerValue").innerText;
            var command = headerValue.substring(1, headerValue.length - 30);
            var now = new Date();

            for (var i = 0; i < Object.keys(botCommandObject[command]).length; i++) {
                var answerName = Object.keys(botCommandObject[command])[i];
                if ($("#toggle" + answerName + "button").hasClass("changed")) {
                    botCommandObject[command][answerName].answer = $("#newOutput" + answerName).val();
                    botCommandObject[command][answerName].lastChangedAnswer = "Last Changed at " + getCurUTCTime(now) + " on " + getCurUTCDate(now) + " (UTC) by " + username;
                }
            }

            postBotCommandsObject();

            document.getElementById("jumbotronContainer").innerHTML = "";
            commandIsOpen = false;

            $('#searchForCommandSelect').val('').trigger('change', [false]);

            document.getElementById("messageContainer").innerHTML =
                '<div class="alert alert-success alert-dismissable fade in">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '<strong>Your changes were successfully commited!</strong>' +
                '</div>';
        } catch (e) {
            document.getElementById("messageContainer").innerHTML =
                '<div class="alert alert-danger alert-dismissable fade in">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '<strong>Error! Please contact the coding stuff on the GHC Discord server and send him this:</strong>' +
                '<p class="mb-0">Exception:' +
                e +
                "Commiting normal changes in botcommand: " + command + '</p>' +
                '</div>';
        }
    } else {
        //delete command object in botCommandJSON
        try {
            delete botCommandObject[openCommand];
            botCommandObject[openCommand] = {};


            //add each default answer
            $(".oldAnswerEdit").each(function(i, e) {
                var answerName = $(e).val();
                var answerOutput = $(e).closest(".row").next("div").children("div").eq(0).children("div").eq(0).children("textarea").val();
                var lastChangedText = $(e).closest(".row").next("div").children("div").eq(0).children("div").eq(1).children("small").text();

                botCommandObject[openCommand][answerName] = {};
                //add answer key and value to answer object
                botCommandObject[openCommand][answerName]["answer"] = answerOutput;
                //add variables object
                botCommandObject[openCommand][answerName]["variables"] = {};
                botCommandObject[openCommand][answerName]["lastChangedAnswer"] = lastChangedText;
                //add each variable object
                $(".availableVariable", $(e).closest(".row").next("div").children("div").eq(1).children("div")).each(function(i, e) {
                    var variableName = e.innerHTML;
                    botCommandObject[openCommand][answerName]["variables"][variableName] = $(e).attr("data-original-title");

                })
            });

            //add each new answer
            $(".newAnswerEdit").each(function(i, e) {
                var answerName = $(e).val();
                var answerOutput = $(e).closest(".row").next("div").children("div").eq(0).children("textarea").val();
                var lastChangedText = $(e).closest(".row").next("div").children("div").eq(0).children("div").eq(1).children("small").text();

                botCommandObject[openCommand][answerName] = {};
                //add answer key and value to answer object
                botCommandObject[openCommand][answerName]["answer"] = answerOutput;
                //add variables object
                botCommandObject[openCommand][answerName]["variables"] = {};
                botCommandObject[openCommand][answerName]["lastChangedAnswer"] = "";
                botCommandObject[openCommand][answerName]["lastChangedAnswer"] = lastChangedText;
                //add each variable object
                $(".availableVariable", $(e).closest(".row").next("div").children("div").eq(1)).each(function(i, e) {
                    var variableName = e.innerHTML;
                    botCommandObject[openCommand][answerName]["variables"][variableName] = $(e).attr("data-original-title");

                })
            });

            document.getElementById("messageContainer").innerHTML =
                '<div class="alert alert-success alert-dismissable fade in">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '<strong>Your changes to the ' + openCommand + ' command were successfully commited!</strong>' +
                '</div>';
        } catch (e) {
            document.getElementById("messageContainer").innerHTML =
                '<div class="alert alert-danger alert-dismissable fade in">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '<strong>Error! Please contact the coding stuff on the GHC Discord server and send him this:</strong>' +
                '<p class="mb-0">Exception:' +
                e +
                "Edit bot command: " + openCommand + '</p>' +
                '</div>';
        }

        postBotCommandsObject();

        document.getElementById("jumbotronContainer").innerHTML = "";
        commandIsOpen = false;
        inEditMode = false;
        editModeCommandErrors = false;
        editModeCommandNew = false;

        document.getElementById("searchForCommandSelect").innerHTML = "";

        fillSelect();
    }
}

/**
 * Fill the <select> element with every command in the botCommandObject
 */
function fillSelect() {
    var select = $("#searchForCommandSelect");

    select.append("<option></option>");
    select.append('<optgroup label="Bot commands" id="optgroupGHCBot"></optgroup>');

    var optgroupGHCBot = $("#optgroupGHCBot");

    for (var i = 0; i < Object.keys(botCommandObject).length; i++) {
        var commandName = Object.keys(botCommandObject)[i];

        optgroupGHCBot.append('<option value="' + commandName + '">!' + commandName + '</option>');
    }
}

/**
 * creates a new jumbotron for the @param command
 */
function createJumbotron(command) {
    document.getElementById("messageContainer").innerHTML = "";
    document.getElementById("jumbotronContainer").innerHTML =
        '<div class="jumbotron botCIJumbotron" id="changeBotCIJumbotron">' +
        '<div class="jumbotronHeader">' +
        '<form class="form-inline">' +
        '<div class="form-group">' +
        '<label class="sr-only">command</label>' +
        '<h3 class="display-3 jumbotronHeaderElement" id="headerValue"><span style="margin-right: 15px; margin-bottom: 15px;">!' + command + ' command</span>' +
        '<button type="button" class="btn btn-default btn-sm jumbotronHeaderElement" id="editCommandBtn" onclick="editCommandButton()" style="margin-right: 30px; margin-bottom: 15px;"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp; Edit</button>' +
        '<button disabled type="button" class="btn btn-primary btn-sm jumbotronHeaderElement" id="commitChanges" onclick="changeBotCommand()" style="margin-bottom: 15px;"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp; Commit changes</button>' +
        '<button type="button" style="display: none; margin-right: 30px;" class="btn btn-danger btn-sm jumbotronHeaderElement" id="deleteCommandBtn" onclick="openDeleteCommandModal()" style="margin-bottom: 15px;"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp; Delete command</button>' +
        '</h3>' +
        '</div>' +
        '</span>' +
        '</div>' +
        '<button class="btn btn-default btn-sm expand closed" id="toggleStyleButton" type="button" style="padding-right: 20px;">Toggle style information</button>' +
        '<div id="styleInfo" style="display: none;">' +
        '<div style="margin-top: 20px;">' +
        '<h4>Variablen in den Text einfügen:<h5>' +
        '<p>Wenn für eine bestimmte Antwort Variablen zur Verfügung stehen, werden sie unter dem Eingabefeld angezeigt.</p>' +
        '<p>Um eine verfügbare Variable einzufügen, schreibe: <strong style="margin-right: 5px; margin-left: 5px;">$[variablenname]</strong>.' +
        '</div>' +
        '<div style="margin-top: 20px;">' +
        '<h4>Es gilt auch das <a href="https://support.discordapp.com/hc/de/articles/210298617-Das-1x1-des-Markdown-Texts-Chatformatierung-fett-kursiv-unterstrichen-" target="_blank">Discord 1x1 des Markdown-Texts</a></h4>' +
        '</div>' +
        '</div>' +
        '<hr>' +
        '<button class="btn btn-success btn-sm pull-right" id="addAnswerToJumbotronBtn" onclick="addAnswerToJumbotron()" style="display: none;"><span class="fa fa-plus fa-lg" aria-hidden="true"></span></button>' +
        '<div id="answersContainer">' +
        '</div>' +
        '</div>';
}

/**
 * Opens the delete command modal
 */
function openDeleteCommandModal() {
    $('#confirmDeleteCommandModal').modal('show');
    $('#commandToDeleteName').html(openCommand);
}

// noinspection JSUnusedGlobalSymbols
/**
 * Deletes the open command
 */
function deleteCommand() {
    $('#confirmDeleteCommandModal').modal('hide');

    delete botCommandObject[openCommand];

    postBotCommandsObject();

    document.getElementById("jumbotronContainer").innerHTML = "";
    commandIsOpen = false;

    document.getElementById("searchForCommandSelect").innerHTML = "";

    fillSelect();

    document.getElementById("messageContainer").innerHTML =
        '<div class="alert alert-success alert-dismissable fade in">' +
        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
        '<strong>You deleted the ' + openCommand + ' command successfully!</strong>' +
        '</div>';
}

/**
 * Only for the createJumbotron method
 *
 * Creates a new answer form inside the
 * @param container with the
 * @param answerName and the
 * @param oldCommandAnswer with the
 * @param lastChangedAnswer attribut
 */
function createAnswer(container, answerName, oldCommandAnswer, lastChangedAnswer) {
    container.append(
        '<span>' +
        '<div class="row">' +
        '<div class="input-group col-xs-10 col-sm-10 col-md-5 col-lg-5" style="margin-top: 25px; margin-bottom: 15px; margin-left: 35px;">' +
        '<span class="deleteAnswerContainer input-group-btn" style="display: none; border: 1px solid black; background-color: #e74c3c; padding-right: 5px; padding-left: 5px;">' +
        '<button class="btn btn-danger btn-xs" onclick="deleteAnswer(this)"><span class="fa fa-trash-o fa-2x" aria-hidden="true"></span></button>' +
        '</span>' +
        '<input disabled onkeyup="checkNewAnswerNameJumbotron(this)" value="' + answerName + '" type="text" class="form-control form-control-sm commandAnswer unchanged" id="' + answerName + 'Answer" placeholder="Enter answer`s name">' +
        '<span class="input-group-btn" style="border: 1px solid black; margin-right: 15px;">' +
        '<button onclick="toggleAnswerJumbotron(this)" class="btn btn-default toggleAnswer expand closed unchanged" id="toggle' + answerName + 'button" type="button" style="padding-right: 10px; border: 0px; background-position: center; padding-left: 50px;">&nbsp; </button>' +
        '</span>' +
        '</div>' +
        '<div class="col-xs-10 col-sm-10 col-md-5 col-lg-5" style="margin-top: 25px; margin-bottom: 15px;">' +
        '<div class="inputFeedbackDanger" id="answerNameFeedback"></div>' +
        '</div>' +
        '</div>' +
        '<div class="answer ' + answerName + '" style="display: none; margin-left: 25px;">' +
        '<div class="answerInputFields">' +
        '<div class="form-group">' +
        '<label for="newOutput' + answerName + '">New output</label>' +
        '<textarea class="form-control" placeholder="Insert Text" id="newOutput' + answerName + '" rows="3" required>' + oldCommandAnswer + '</textarea>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="oldOutput' + answerName + '">Old output</label>' +
        '<small>' + lastChangedAnswer + '</small>' +
        '<textarea class="form-control" readonly id="oldOutput' + answerName + '" rows="3" required>' + oldCommandAnswer + '</textarea>' +
        '</div>' +
        '</div>' +
        '<div class="form-group" id="varsFormGroup' + answerName + '">' +
        '<label for="variablesContainer' + answerName + '" class="variablesLabel">Available variables</label>' +
        '<div id="variablesContainer' + answerName + '" class="varContainer">' +
        '</div>' +
        '</div>' +
        '</span>'
    );
}

/**
 * Creates a new available variable form inside the @param varsContainer
 * with the @param varName and with the @param varIdentifier
 */
function createAvailableVariable(varsContainer, varName, varIdentifier) {
    varsContainer.append(
        '<button type="button" class="btn btn-link availableVariable" data-toggle="tooltip" data-placement="bottom" title="' + varName + '">' + varIdentifier + '</button>'
    );
}

// noinspection JSUnusedGlobalSymbols
/**
 * Adds a new variable to a jumbotron or the new command modal
 */
function addVariable() {
    var varIdentifier = $('#newVariableIdentifier').val();
    var varDefinition = $('#newVariableDescription').val();
    $(lastClickedAddVariableBtn).closest("label").next("div").prepend(
        '<button type="button" class="btn btn-link availableVariable" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="' + varDefinition + '">' +
        varIdentifier +
        '</button>'
    );
    $('#addVariable').modal('hide');
    $('[data-toggle="tooltip"]').tooltip();

    if (!newCommandIsOpen) {
        checkCommandInEditMode();
    }
}

/**
 * Checks if the edit mode command has changed
 */
function checkCommandInEditMode() {
    var editCommand = {};
    editCommand[openCommand] = {};

    //add each default answer
    $(".oldAnswerEdit").each(function(i, e) {
        var answerName = $(e).val();
        var answerOutput = $(e).closest(".row").next("div").children("div").eq(0).children("div").eq(0).children("textarea").val();
        var lastChangedText = $(e).closest(".row").next("div").children("div").eq(0).children("div").eq(1).children("small").text();

        editCommand[openCommand][answerName] = {};
        //add answer key and value to answer object
        editCommand[openCommand][answerName]["answer"] = answerOutput;
        editCommand[openCommand][answerName]["lastChangedAnswer"] = lastChangedText;
        //add variables object
        editCommand[openCommand][answerName]["variables"] = {};
        //add each variable object
        $(".availableVariable", $(e).closest(".row").next("div").children("div").eq(1).children("div")).each(function(i, e) {
            var variableName = e.innerHTML;
            editCommand[openCommand][answerName]["variables"][variableName] = $(e).attr("data-original-title");

        })
    });

    //add each new answer
    $(".newAnswerEdit").each(function(i, e) {
        var answerName = $(e).val();
        var answerOutput = $(e).closest(".row").next("div").children("div").eq(0).children("textarea").val();
        var lastChangedText = $(e).closest(".row").next("div").children("div").eq(0).children("div").eq(1).children("small").text();

        editCommand[openCommand][answerName] = {};
        //add answer key and value to answer object
        editCommand[openCommand][answerName]["answer"] = answerOutput;
        editCommand[openCommand][answerName]["lastChangedAnswer"] = lastChangedText;
        //add variables object
        editCommand[openCommand][answerName]["variables"] = {};

        //add each variable object
        $(".availableVariable", $(e).closest(".row").next("div").children("div").eq(1)).each(function(i, e) {
            var variableName = e.innerHTML;
            editCommand[openCommand][answerName]["variables"][variableName] = $(e).attr("data-original-title");

        })
    });

    editModeCommandNew = (JSON.stringify(editCommand[openCommand]) !== JSON.stringify(botCommandObject[openCommand]));

    setCommitChangesBtnEditMode();
}

/**
 * Controls the commit changes button while command is in edit mode
 */
function setCommitChangesBtnEditMode() {
    if (!editModeCommandErrors && editModeCommandNew) {
        $("#commitChanges").prop("disabled", false);
    } else {
        $("#commitChanges").prop("disabled", true);
    }
}

/**
 * Opens the new variable modal
 */
function openAddVariableJumbotron(element) {
    lastClickedAddVariableBtn = element;
    $('#addVariable').modal('show');
    $("#newVariableIdentifier").val("");
    $("#newVariableDescription").val("");
    checkVariableIdentifier(document.getElementById("newVariableIdentifier"));
}

/**
 * Opens the delete variable modal
 */
function openDeleteVariableModal(element) {
    lastClickedAddVariableBtn = element;
    $('#deleteVariable').modal('show');

    lastOpenedVarContainer = $(element).closest("label").next("div");
    var deleteVarContainer = $("#deleteVariableContainer");
    deleteVarContainer.html("");

    $(".availableVariable", lastOpenedVarContainer).each(function(key, value) {
        var varName = value.innerHTML;
        deleteVarContainer.append(
            '<tr><th style="font-size: 18px;">' + varName + '</th>' +
            '<td><button class="btn btn-danger btn-xs" id="' + varName + '" style="float: right;" onclick="deleteVariable(this)">' +
            '<span class="fa fa-trash-o fa-lg" aria-hidden="true"></span></td></tr>'
        );
    });
}

/**
 * Deletes a variable
 */
function deleteVariable(btn) {
    var varNameToDelete = $(btn).attr('id');
    $(btn).closest("td").closest("tr").remove();
    $(".availableVariable", lastOpenedVarContainer).each(function(key, value) {
        var varName = value.innerHTML;
        if (varName === varNameToDelete) {
            $(value).remove();
        }
    });

    if (!newCommandIsOpen) {
        checkCommandInEditMode();
    }
}

/**
 * Adds an answer field to the open jumbotron
 */
function addAnswerToJumbotron() {
    $("#answersContainer").prepend(
        '<span>' +
        '<div class="row">' +
        '<div class="input-group col-xs-10 col-sm-10 col-md-5 col-lg-5" style="margin-top: 25px; margin-bottom: 15px; margin-left: 35px;">' +
        '<span class="deleteAnswerContainer input-group-btn" style="display: table-cell; border: 1px solid black; background-color: #e74c3c; padding-right: 5px; padding-left: 5px;">' +
        '<button class="btn btn-danger btn-xs" onclick="deleteAnswer(this)"><span class="fa fa-trash-o fa-2x" aria-hidden="true"></span></button>' +
        '</span>' +
        '<input type="text" onkeyup="checkNewAnswerNameJumbotron(this)" class="form-control form-control-sm commandAnswer unchanged openCommandError newAnswerEdit" id="newAnswerAnswer" placeholder="Enter answer`s name">' +
        '<span class="input-group-btn" style="border: 1px solid black; margin-right: 15px;">' +
        '<button onclick="toggleAnswerJumbotron(this)" class="btn btn-default toggleAnswer expand closed unchanged" id="toggleNewAnswerbutton" type="button" style="padding-right: 10px; border: 0; background-position: center; padding-left: 50px;">&nbsp; </button>' +
        '</span>' +
        '</div>' +
        '<div class="col-xs-10 col-sm-10 col-md-5 col-lg-5" style="margin-top: 25px; margin-bottom: 15px;">' +
        '<div class="inputFeedbackDanger" id="answerNameFeedback">Der Name () darf nicht leer sein und keine Leerzeichen enthalten!</div>' +
        '</div>' +
        '</div>' +
        '<div class="answer newAnswer" style="display: none; margin-left: 25px;">' +
        '<div class="form-group">' +
        '<label for="defaultOutput">Default Output</label>' +
        '<textarea class="form-control" id="defaultOutput" rows="3" required></textarea>' +
        '</div>' +
        '<div class="form-group" id="varsFormGroupNewAnswer">' +
        '<label for="variablesContainerNewAnswer" class="variablesLabel"><span style="margin-right: 10px;">Available variables</span>' +
        '<button style="margin-right: 10px;" class="btn btn-success btn-xs" onclick="openAddVariableJumbotron(this)">' +
        '<span class="fa fa-plus" aria-hidden="true"></span>' +
        '</button>' +
        '<button class="btn btn-danger btn-xs" onclick="openDeleteVariableModal(this)">' +
        '<span class="fa fa-minus" aria-hidden="true"></span>' +
        '</button></label>' +
        '<div id="variablesContainerNewAnswer">' +
        '</div>' +
        '</div>' +
        '</span>'
    );
    checkCommandInEditMode();
    setCommitButton(false);

}

/**
 * Toggles next answer container in jumbotron
 */
function toggleAnswerJumbotron(element) {
    $(element).closest("span").closest("div").closest(".row").next("div").slideToggle();
    $(element).toggleClass("closed open");
}

// noinspection JSUnusedGlobalSymbols
/**
 * Checks the input of the 'reset bot commands' object
 */
function checkResetInput(text) {
    if (text === "RESET") {
        document.getElementById("resetButton").disabled = false;
    } else {
        if (!document.getElementById("resetButton").disabled) {
            document.getElementById("resetButton").disabled = true;
        }
    }
}

/**
 * Checks the variable identifier of a new variable
 */
function checkVariableIdentifier(element) {
    var addVarBtn = $("#addVarButton");
    var unique = true;
    var name = element.value;
    var feedback = $("#variableIdentifierFeedback");

    if (name.indexOf(" ") >= 0 || name === "") {
        feedback.text("Der Identifier (" + name + ") darf nicht leer sein und keine Leerzeichen enthalten!");
        if (!$(element).hasClass("dangerInput")) {
            $(element).addClass("dangerInput");
        }

        addVarBtn.prop("disabled", true);
        return;
    }

    $(".availableVariable", $(lastClickedAddVariableBtn).closest("label").next("div")).each(function(i, e) {
        if (e.innerHTML === name) {
            unique = false;
        }
    });

    if (unique) {
        feedback.text("");
        if ($(element).hasClass("dangerInput")) {
            $(element).removeClass("dangerInput");
        }
        addVarBtn.prop("disabled", false);
    } else {
        feedback.text("Der Identifier '" + name + "' exsistiert bereits!");
        if (!$(element).hasClass("dangerInput")) {
            $(element).addClass("dangerInput");
        }
        addVarBtn.prop("disabled", true);
    }
}

// noinspection JSUnusedGlobalSymbols
/**
 * Resets all bot commands and resorts the botCI's content
 */
function resetBotCommands() {
    $.getJSON(botCommandsFolderURL + language + "/botCommandsBackup.json").then(function(data) {
        botCommandObject = data;

        postBotCommandsObject();

        document.getElementById("searchForCommandSelect").innerHTML = "";
        document.getElementById("jumbotronContainer").innerHTML = "";

        commandIsOpen = false;

        fillSelect();

        $('#confirmResetModal').modal('hide');

        document.getElementById("messageContainer").innerHTML =
            '<div class="alert alert-danger alert-dismissable fade in">' +
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
            '<strong>Reset bot commands!</strong>' +
            '</div>';
    });
}

/**
 * POST botCommandsObject
 */
function postBotCommandsObject() {
    $.ajax({
        url: botCIPHPURL + "writeBotCommands/",
        type: "POST",
        data: { botCommandJSON: JSON.stringify(botCommandObject) },
        //contentType: "application/json",
        dataType: "html",
        error: function(jqXHR, textStatus, errorThrown) {
            alert('ajax method | Error posting Data (Look inside the console!)');
            console.log("///////////////////////////////////////////");
            console.log("post bot commands object!");
            console.log("///////////////////////////////////////////");
            console.log("jqXHR:");
            console.log(jqXHR);
            console.log("textStatus:");
            console.log(textStatus);
            console.log("errorThrown:");
            console.log(errorThrown);
            console.log("json string:");
            console.log(JSON.stringify(botCommandObject));
        }
    });
}

/**
 * POST botCommandsObjectBackup
 */
function postBotCommandsObjectBackup() {
    $.ajax({
        url: botCIPHPURL + "writeBotCommandsBackup/",
        type: "POST",
        data: { botCommandJSONBackup: JSON.stringify(botCommandObjectBackup) },
        //contentType: "application/json",
        dataType: "html",
        error: function(jqXHR, textStatus, errorThrown) {
            alert('ajax method | Error posting Data (Look inside the console!)');
            console.log("///////////////////////////////////////////");
            console.log("post bot commands backup object!");
            console.log("///////////////////////////////////////////");
            console.log("jqXHR:");
            console.log(jqXHR);
            console.log("textStatus:");
            console.log(textStatus);
            console.log("errorThrown:");
            console.log(errorThrown);
        }
    });
}