@extends('dashboardui.layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container">

    <!-- ============================= BUTTONS ================================= -->

    <h4 class="u-mb-medium">Buttons</h4>
    <div class="row">
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--success c-btn--fullwidth" href="#">Button</a>
        </div>
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--info c-btn--fullwidth" href="#">Button</a>
        </div>
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--secondary c-btn--fullwidth" href="#">Button</a>
        </div>
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--fancy c-btn--fullwidth" href="#">Button</a>
        </div>
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--danger c-btn--fullwidth" href="#">Button</a>
        </div>
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--primary c-btn--fullwidth" href="#">Button</a>
        </div>
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--warning c-btn--fullwidth" href="#">Button</a>
        </div>
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--fullwidth is-disabled" >Button</a>
        </div>
    </div>

    <div class="row u-mb-large">
        <div class="col u-mb-medium">
            <a class="c-btn c-btn--info" href="#!">
                <i class="fa fa-envelope-o u-mr-xsmall"></i>Send
            </a>
        </div>

        <div class="col u-mb-medium">
            <div class="c-dropdown dropdown">
                <button class="c-btn c-btn--success has-dropdown dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                </div>
            </div>
        </div>

        <div class="col u-mb-medium">
            <div class="c-dropdown dropdown">
                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                </div>
            </div>
        </div>

        <div class="col u-mb-medium">
            <a class="c-btn c-btn--danger" href="#!">
                <i class="fa fa-trash-o u-mr-xsmall"></i>Delete
            </a>
        </div>

        <div class="col u-mb-medium">
            <a class="c-btn c-btn--danger is-disabled" href="#!">
                <i class="fa fa-trash-o u-mr-xsmall"></i>Delete
            </a>
        </div>

        <div class="col u-mb-medium">
            <div class="c-btn-group">
                <a class="c-btn c-btn--success" href="#!">
                    <i class="fa fa-plus u-mr-xsmall"></i>Add
                </a>

                <a class="c-btn c-btn--success" href="#!">
                    <i class="fa fa-pencil-square-o u-mr-xsmall"></i>Edit
                </a>

                <a class="c-btn c-btn--success" href="#!">
                    <i class="fa fa-trash-o u-mr-xsmall"></i>Delete
                </a>
            </div>
        </div>
    </div>

    <!-- ============================== FORMS ================================== -->

    <h4 class="u-mb-medium">Form Elements</h4>

    <div class="row">
        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label u-hidden-visually" for="input1">Label</label>
                <input class="c-input" id="input1" type="text" placeholder="Placeholder">
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label u-hidden-visually" for="input2">Label</label>
                <input class="c-input" id="input2" type="text" placeholder="Placeholder">
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label u-hidden-visually" for="input3">Label</label>
                <input class="c-input" id="input3" type="text" placeholder="Placeholder">
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label u-hidden-visually" for="input4">Label</label>
                <input class="c-input" id="input4" type="text" placeholder="Placeholder">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field has-icon-right">
                <label class="c-field__label u-hidden-visually" for="input5">Warning Input</label>
                <input class="c-input c-input--warning" id="input5" type="text" value="Warning" placeholder="Warning">
                <span class="c-field__icon">
                            <i class="fa fa-exclamation-triangle u-color-warning"></i>
                        </span>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field has-icon-right">
                <label class="c-field__label u-hidden-visually" for="input6">Success Input</label>
                <input class="c-input c-input--success" id="input6" type="text" value="Success" placeholder="Success">
                <span class="c-field__icon">
                            <i class="fa fa-check u-color-success"></i>
                        </span>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field has-icon-right">
                <label class="c-field__label u-hidden-visually" for="input7">Danger Input</label>
                <input class="c-input c-input--danger" id="input7" type="text" value="Danger" placeholder="Danger">
                <span class="c-field__icon">
                            <i class="fa fa-times u-color-danger"></i>
                        </span>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label u-hidden-visually" for="input8">Disabled Input</label>
                <input class="c-input" id="input8" type="text" placeholder="Disabled" disabled>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field has-addon-left">
                <span class="c-field__addon"><i class="fa fa-calendar"></i></span>
                <label class="c-field__label u-hidden-visually" for="input9">Disabled Input</label>
                <input class="c-input" data-toggle="datepicker" id="input9" type="text" placeholder="Focus to open caleendar">
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field has-addon-right">
                <label class="c-field__label u-hidden-visually" for="input10">Disabled Input</label>
                <input class="c-input" id="input10" type="text" placeholder="Clark">
                <span class="c-field__addon"><i class="fa fa-bullhorn"></i></span>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field has-icon-left">
                        <span class="c-field__icon">
                            <i class="fa fa-user-o"></i>
                        </span>
                <label class="c-field__label u-hidden-visually" for="input11">Disabled Input</label>
                <input class="c-input" id="input11" type="text" placeholder="Clark">
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field has-icon-right">
                        <span class="c-field__icon">
                            <i class="fa fa-calendar"></i>
                        </span>
                <label class="c-field__label u-hidden-visually" for="input12">Disabled Input</label>
                <input class="c-input" data-toggle="datepicker" id="input12" type="text" placeholder="Focus to open calendar">
            </div>
        </div>
    </div><!-- // .row -->

    <div class="row u-mb-medium">
        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label" for="input13">Label</label>
                <input class="c-input" type="text" id="input13" placeholder="Clark">
                <small class="c-field__message">
                    <i class="fa fa-info-circle"></i>This is a required field
                </small>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label" for="input14">Label</label>
                <input class="c-input" id="input14" type="text" placeholder="Clark">
                <small class="c-field__message u-color-success">
                    <i class="fa fa-check-circle"></i>Positive Feedback
                </small>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label" for="input15">Label</label>
                <input class="c-input" id="input15" type="text" placeholder="Clark">
                <small class="c-field__message u-color-danger">
                    <i class="fa fa-times-circle"></i>Danger Feedback
                </small>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-small">
            <div class="c-field">
                <label class="c-field__label" for="input16">Label</label>
                <input class="c-input" id="input16" type="text" placeholder="Clark">
                <small class="c-field__message u-color-warning">
                    <i class="fa fa-exclamation-circle"></i> Warning Feedback
                </small>
            </div>
        </div>
    </div><!-- // .row -->

    <div class="row">
        <div class="col-sm-6 col-md-3 u-mb-medium">
            <div class="c-form-field">
                <label class="c-field__label" for="input17">Label</label>
                <input class="c-input c-input--warning" id="input17" type="text" placeholder="Warning">
            </div>
        </div>
        <div class="col-sm-6 col-md-3 u-mb-medium">
            <div class="c-field">
                <label class="c-field__label" for="input18">Label</label>
                <input class="c-input c-input--success" id="input18" type="text" placeholder="Success">
            </div>
        </div>
        <div class="col-sm-6 col-md-3 u-mb-medium">
            <div class="c-field">
                <label class="c-field__label" for="input19">Label</label>
                <input class="c-input c-input--danger" id="input19" type="text" placeholder="Error">
            </div>
        </div>
        <div class="col-sm-6 col-md-3 u-mb-medium">
            <div class="c-field">
                <label class="c-field__label" for="input20">Label</label>
                <input class="c-input" id="input20" type="text" placeholder="Disabled" disabled>
            </div>
        </div>
    </div><!-- // .row -->

    <div class="row">
        <div class="col-md-3 u-mb-medium">
            <div class="c-field">
                <label  class="c-field__label" for="textarea1">Label</label>
                <textarea class="c-input c-input--disabled" id="textarea1" disabled>The textarea tag defines a multi-line text input control.</textarea>
            </div>
        </div>

        <div class="col-md-3 u-mb-medium">
            <div class="c-field">
                <label  class="c-field__label" for="textarea2">Label</label>
                <textarea class="c-input" id="textarea2">The textarea tag defines a multi-line text input control.</textarea>
            </div>
        </div>

        <div class="col-md-3 u-mb-medium">
            <div class="c-field">
                <label  class="c-field__label" for="textarea3">Label</label>
                <textarea class="c-input c-input--danger" id="textarea3">The textarea tag defines a multi-line text input control.</textarea>
            </div>
        </div>

        <div class="col-md-3 u-mb-medium">
            <div class="c-field">
                <label  class="c-field__label" for="textarea4">Label</label>
                <textarea class="c-input" id="textarea4">The textarea tag defines a multi-line text input control.</textarea>
                <small class="c-field__message">This field is required</small>
            </div>
        </div>
    </div><!-- // .row -->

    <div class="row u-mb-medium">
        <div class="col-sm-6 col-md-3 u-mb-medium">
            <p class="u-text-mute u-text-uppercase u-mb-small">Checkboxes</p>
            <form>
                <div class="c-choice c-choice--checkbox">
                    <input class="c-choice__input" id="checkbox1" name="checkboxes" type="checkbox">
                    <label class="c-choice__label" for="checkbox1">Checkbox 1</label>
                </div>

                <div class="c-choice c-choice--checkbox">
                    <input class="c-choice__input" id="checkbox2" name="checkboxes" type="checkbox">
                    <label class="c-choice__label" for="checkbox2">Checkbox 1</label>
                </div>

                <div class="c-choice c-choice--checkbox">
                    <input class="c-choice__input" id="checkbox3" name="checkboxes" type="checkbox">
                    <label class="c-choice__label" for="checkbox3">Checkbox 1</label>
                </div>

                <div class="c-choice c-choice--checkbox is-disabled">
                    <input class="c-choice__input" id="checkbox4" name="checkboxes" type="checkbox" disabled>
                    <label class="c-choice__label" for="checkbox4">Disabled Checkbox</label>
                </div>
            </form>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-medium">
            <p class="u-text-mute u-text-uppercase u-mb-small">Radio Buttons</p>

            <form class="u-mb-medium">
                <div class="c-choice c-choice--radio">
                    <input class="c-choice__input" id="radio1" name="radios" type="radio">
                    <label class="c-choice__label" for="radio1">Radio 1</label>
                </div>

                <div class="c-choice c-choice--radio">
                    <input class="c-choice__input" id="radio2" name="radios" type="radio">
                    <label class="c-choice__label" for="radio2">Radio 2</label>
                </div>

                <div class="c-choice c-choice--radio">
                    <input class="c-choice__input" id="radio3" name="radios" type="radio">
                    <label class="c-choice__label" for="radio3">Radio 3</label>
                </div>

                <div class="c-choice c-choice--radio is-disabled">
                    <input class="c-choice__input" id="radio4" name="radios" type="radio" disabled>
                    <label class="c-choice__label" for="radio4">Disabled Radio</label>
                </div>
            </form>
        </div>

        <div class="col-sm-6 col-md-3 u-mb-medium">
            <p class="u-text-mute u-text-uppercase u-mb-small">Switches</p>

            <div class="u-block u-mb-xsmall">
                <div class="c-switch">
                    <input class="c-switch__input" id="switch1" type="checkbox">
                    <label class="c-switch__label" for="switch1">Off</label>
                </div>
            </div>

            <div class="u-block u-mb-xsmall">
                <div class="c-switch is-active">
                    <input class="c-switch__input" id="switch2" type="checkbox" checked="checked">
                    <label class="c-switch__label" for="switch2">This is a long text</label>
                </div>
            </div>

            <div class="u-block">
                <div class="c-switch is-disabled">
                    <input class="c-switch__input" id="switch3" type="checkbox">
                    <label class="c-switch__label" for="switch3">Disabled</label>
                </div>
            </div>

        </div>

        <div class="col-sm-6 col-md-3 u-mb-medium">
            <p class="u-text-mute u-text-uppercase u-mb-small">Toggles</p>

            <div class="c-toggle u-mb-small">
                <div class="c-toggle__btn is-active">
                    <label class="c-toggle__label" for="toggle1">
                        <input class="c-toggle__input" id="toggle1" name="toggles" type="radio" checked>Yes
                    </label>
                </div>

                <div class="c-toggle__btn">
                    <label class="c-toggle__label" for="toggle2">
                        <input class="c-toggle__input" id="toggle2" name="toggles" type="radio">No
                    </label>
                </div>
            </div><!-- // .c-toggle -->

            <div class="c-toggle is-disabled">
                <div class="c-toggle__btn">
                    <label class="c-toggle__label" for="toggle3">
                        <input class="c-toggle__input" id="toggle3" name="toggles-disabled" type="radio" disabled>Yes
                    </label>
                </div>

                <div class="c-toggle__btn">
                    <label class="c-toggle__label" for="toggle4">
                        <input class="c-toggle__input" id="toggle4" name="toggles-disabled" type="radio" disabled>No
                    </label>
                </div>
            </div><!-- // .c-toggle -->

        </div><!-- // .col-sm-3 -->
    </div><!-- // .row -->

    <div class="row u-mb-xlarge">
        <div class="col-md-4 u-mb-medium">
            <p class="u-text-mute u-text-uppercase u-mb-small">Selects</p>

            <div class="c-field u-mb-medium">
                <label class="c-field__label" for="select1">Single Select</label>

                <!-- Select2 jquery plugin is used -->
                <select class="c-select" id="select1">
                    <option>First</option>
                    <option>Second</option>
                    <option>Third</option>
                </select>
            </div>

            <div class="c-field u-mb-medium">
                <label class="c-field__label" for="select2">Single Select (Search Enabled)</label>

                <!-- Select2 jquery plugin is used -->
                <select class="c-select has-search" id="select2">
                    <option>First</option>
                    <option>Second</option>
                    <option>Third</option>
                </select>
            </div>

            <div class="c-field u-mb-medium">
                <label class="c-field__label" for="select3">Multiple Select (Search Enabled)</label>

                <!-- Select2 jquery plugin is used -->
                <select class="c-select c-select--multiple" id="select3">
                    <option>First</option>
                    <option>Second</option>
                    <option>Third</option>
                </select>
            </div>

            <div class="c-field u-mb-medium">
                <label class="c-field__label" for="select4">Disabled Select</label>

                <select class="c-select is-disabled" id="select4" disabled>
                    <option>First</option>
                    <option>Second</option>
                    <option>Third</option>
                </select>
            </div>
        </div>

        <div class="col-md-8">
            <p class="u-text-mute u-text-uppercase u-mb-medium">Dropdowns</p>

            <div class="row">
                <div class="col-md-4 u-mb-medium">
                    <div class="c-dropdown dropdown">
                        <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                        <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton5">
                            <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                            <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                            <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 u-text-center u-mb-medium">
                    <div class="c-dropdown dropdown">
                        <div class="c-avatar has-dropdown dropdown-toggle" id="dropdownMenuButton2" data-toggle="dropdown" role="menu" aria-haspopup="true" aria-expanded="false">
                            <img class="c-avatar__img" src="img/avatar-150.jpg" alt="Name">
                        </div>

                        <div class="c-dropdown__menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">
                            <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                            <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                            <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 u-text-center u-mb-medium">
                    <div class="c-dropdown dropdown">
                        <div class="c-avatar has-dropdown dropdown-toggle" id="dropdownMenuButton3" data-toggle="dropdown" role="menu" aria-haspopup="true" aria-expanded="false">
                            <img class="c-avatar__img" src="img/avatar2-200.jpg" alt="Name">
                        </div>

                        <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton3">
                            <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                            <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                            <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <p class="u-text-mute u-text-uppercase u-mb-medium">File Upload</p>

                    <form action="/file-upload" class="dropzone" id="custom-dropzone" style="height: 180px;">
                        <div class="dz-message" data-dz-message>
                            <i class="dz-icon fa fa-cloud-upload"></i>
                            <span>Drag a file here or browse for a file to upload.</span>
                        </div>

                        <div class="fallback">
                            <input name="file" type="file" multiple>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- // .row -->

    <!-- ================================ MODALS =============================== -->

    <h4 class="u-mb-medium">Modals</h4>

    <div class="row u-mb-xlarge">
        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--success" data-toggle="modal" data-target="#myModal1">
                Launch Onboard modal
            </button>

            <!-- Modal -->
            <div class="c-modal c-modal--xlarge modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModal1" data-backdrop="static">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="modal-content">

                        <header class="c-modal__header">
                            <h1 class="c-modal__title">Welcome To Our App</h1>
                            <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-close"></i>
                                    </span>
                        </header>

                        <div class="u-overflow-x-auto u-width-100">
                            <div class="c-modal__subheader" style="width:750px;">
                                <nav class="c-counter-nav c-counter-nav--inverse">
                                    <p class="c-counter-nav__title">Status:</p>
                                    <div class="c-counter-nav__item">
                                        <a class="c-counter-nav__link" href="#">
                                            <span class="c-counter-nav__counter"><i class="fa fa-check"></i></span>Contract
                                        </a>
                                    </div>
                                    <div class="c-counter-nav__item">
                                        <a class="c-counter-nav__link" href="#">
                                            <span class="c-counter-nav__counter"><i class="fa fa-check"></i></span>Initial Design Draft
                                        </a>
                                    </div>
                                    <div class="c-counter-nav__item">
                                        <a class="c-counter-nav__link is-active" href="#">
                                            <span class="c-counter-nav__counter">3</span>Coding Sprint
                                        </a>
                                    </div>
                                    <div class="c-counter-nav__item">
                                        <a class="c-counter-nav__link" href="#">
                                            <span class="c-counter-nav__counter">4</span>SEO Optimization
                                        </a>
                                    </div>
                                </nav>
                            </div>
                        </div>

                        <div class="c-modal__body u-text-center u-pb-small">
                            <div class="row u-justify-center">
                                <div class="col-sm-8">
                                    <h2 class="u-h5 u-mb-small">Start your first board</h2>
                                    <p class="u-text-mute u-mb-xsmall">Click on the right panel and start your first board. Boards are our core feature for helping you keep your work in order and easy to manage.</p>
                                </div>
                            </div>

                            <div class="o-line">
                                <a class="u-text-mute u-align-self-end" href="#">Skip</a>

                                <img src="img/onboard.png" class="u-width-75 u-hidden-down@mobile" style="transform:translate(30px, 30px);" alt="About image">

                                <a class="c-btn c-btn--green u-align-self-end" href="#">Next</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--info" data-toggle="modal" data-target="#myModal2">
                Launch Pricing modal
            </button>

            <!-- Modal -->
            <div class="c-modal c-modal--huge modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModal2">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="c-modal__content modal-content">
                        <a class="c-modal__close c-modal__close--absolute u-text-mute u-opacity-medium" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-close"></i>
                        </a>

                        <div class="c-modal__body">

                            <div class="row u-justify-center">
                                <div class="col-md-7">
                                    <div class="u-mt-medium u-text-center">
                                        <h4 class="u-mb-xsmall">Small startup? Big company? We’ve got a plan.</h4>
                                        <p class="u-text-mute u-mb-large">Pricing is based on two things: Number of opened projects, and the number of seats you want for your team. Plans start at $19/month.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-md-3 u-nospace">

                                    <article class="c-plan">
                                        <img class="c-plan__img" src="img/price-icon.svg" alt="Pricing Icon">

                                        <h5 class="c-plan__title">Basic</h5>

                                        <h4 class="c-plan__price">
                                            $19<span class="u-text-mute u-h6">/month</span></h4>

                                        <h5 class="c-plan__note">max. 1 user</h5>

                                        <span class="c-plan__divider"></span>

                                        <ul>
                                            <li class="c-plan__feature">
                                                <span>10</span> Projects
                                            </li>
                                            <li class="c-plan__feature">
                                                <span>3</span> Clients
                                            </li>
                                            <li class="c-plan__feature">
                                                <span>Unlimited</span> Messages
                                            </li>
                                        </ul>
                                    </article><!-- // .c-plan -->

                                </div>

                                <div class="col-sm-6 col-md-3 u-nospace">

                                    <article class="c-plan">
                                        <img class="c-plan__img" src="img/price-icon2.svg" alt="Pricing Icon">

                                        <h5 class="c-plan__title">Basic</h5>

                                        <h4 class="c-plan__price">
                                            $19<span class="u-text-mute u-h6">/month</span></h4>

                                        <h5 class="c-plan__note">max. 1 user</h5>

                                        <span class="c-plan__divider"></span>

                                        <ul>
                                            <li class="c-plan__feature">
                                                <strong>10</strong> Projects
                                            </li>
                                            <li class="c-plan__feature">
                                                <strong>3</strong> Clients
                                            </li>
                                            <li class="c-plan__feature">
                                                <strong>Unlimited</strong> Messages
                                            </li>
                                        </ul>
                                    </article><!-- // .c-plan -->

                                </div>

                                <div class="col-sm-6 col-md-3 u-nospace">

                                    <article class="c-plan">
                                        <img class="c-plan__img" src="img/price-icon3.svg" alt="Pricing Icon">

                                        <h5 class="c-plan__title">Basic</h5>

                                        <h4 class="c-plan__price">
                                            $19<span class="u-text-mute u-h6">/month</span></h4>

                                        <h5 class="c-plan__note">max. 1 user</h5>

                                        <span class="c-plan__divider"></span>

                                        <ul>
                                            <li class="c-plan__feature">
                                                <span>10</span> Projects
                                            </li>
                                            <li class="c-plan__feature">
                                                <span>3</span> Clients
                                            </li>
                                            <li class="c-plan__feature">
                                                <span>Unlimited</span> Messages
                                            </li>
                                        </ul>
                                    </article><!-- // .c-plan -->

                                </div>

                                <div class="col-sm-6 col-md-3 u-nospace">

                                    <article class="c-plan">
                                        <img class="c-plan__img" src="img/price-icon4.svg" alt="Pricing Icon">

                                        <h5 class="c-plan__title">Basic</h5>

                                        <h4 class="c-plan__price">
                                            $19<span class="u-text-mute u-h6">/month</span></h4>

                                        <h5 class="c-plan__note">max. 1 user</h5>

                                        <span class="c-plan__divider"></span>

                                        <ul>
                                            <li class="c-plan__feature">
                                                <span>10</span> Projects
                                            </li>
                                            <li class="c-plan__feature">
                                                <span>3</span> Clients
                                            </li>
                                            <li class="c-plan__feature">
                                                <span>Unlimited</span> Messages
                                            </li>
                                        </ul>
                                    </article><!-- // .c-plan -->

                                </div>

                            </div>

                            <p class="u-text-mute u-text-center">Have a larger team? <a href="#">Contact us</a> for information about more enterprise options.</p>
                        </div><!-- // .c-plans__body -->


                        <footer class="c-modal__footer u-justify-center">
                            <a class="c-btn c-btn--green" href="#">Start your free trial</a>
                        </footer>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--secondary" data-toggle="modal" data-target="#myModal3">
                Launch Simple Modal
            </button>

            <!-- Modal -->
            <div class="c-modal modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModal3">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="c-card u-p-medium u-mh-auto" style="max-width:500px;">
                            <h3>Modal Title</h3>
                            <p class="u-text-mute u-mb-small">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum similique, dolores dolorem perferendis inventore illum dicta. Nostrum, officiis alias distinctio deleniti aliquid eum dolorum sed fugit omnis molestias! Magnam, animi.</p>
                            <button class="c-btn c-btn--info" data-dismiss="modal">
                                Ok, just close this modal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row u-mb-xlarge">
        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--success" data-toggle="modal" data-target="#standard-modal">
                Standard Modal
            </button>

            <!-- Modal -->
            <div class="c-modal modal fade" id="standard-modal" tabindex="-1" role="dialog" aria-labelledby="standard-modal" data-backdrop="static">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="c-modal__content">

                        <div class="c-modal__header">
                            <h3 class="c-modal__title">This is the modal title</h3>

                            <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-close"></i>
                                    </span>
                        </div>

                        <div class="c-modal__subheader">
                            <p>This is the sub header title</p>
                        </div>

                        <div class="c-modal__body">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo, ratione quibusdam? Consequuntur natus praesentium adipisci eos, reiciendis distinctio error nostrum animi quos hic perferendis eius fugiat fuga sunt fugit deserunt!</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis eveniet modi excepturi error nesciunt cupiditate tempora deserunt perspiciatis exercitationem, suscipit temporibus officia sit recusandae autem iure vero neque quia consequatur!</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo, ratione quibusdam? Consequuntur natus praesentium adipisci eos, reiciendis distinctio error nostrum animi quos hic perferendis eius fugiat fuga sunt fugit deserunt!</p>
                        </div>

                        <div class="c-modal__footer">
                            <p>This is the modal footer</p>
                        </div>

                    </div><!-- // .c-modal__content -->
                </div><!-- // .c-modal__dialog -->
            </div><!-- // .c-modal -->
        </div>

        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--info" data-toggle="modal" data-target="#modal5">
                Achievements Modal
            </button>

            <!-- Modal -->
            <div class="c-modal modal fade" id="modal5" tabindex="-1" role="dialog" aria-labelledby="modal5" data-backdrop="static">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="c-modal__content">

                        <div class="c-modal__header">
                            <h3 class="c-modal__title">My Achievements</h3>

                            <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-close"></i>
                                    </span>
                        </div>

                        <div class="c-modal__subheader">
                            <p>You have 3 awards so far. Keep it up!</p>
                        </div>

                        <div class="c-modal__body c-modal__body--maximized">
                            <div class="row o-line u-border-bottom u-pv-small">
                                <div class="col-sm-9">
                                    <div class="o-media u-mb-xsmall">
                                        <div class="o-media__img u-mr-xsmall">
                                            <img src="img/medal1.svg" alt="Lake King Badge">
                                        </div>

                                        <div class="o-media__body">
                                                    <span class="u-text-mute u-text-small u-text-uppercase">
                                                        Secret Award
                                                    </span>
                                            <p>Something really secret here...</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <a href="#" class="c-btn c-btn--info c-btn--fullwidth">
                                        Share
                                    </a>
                                </div>
                            </div>

                            <div class="row o-line u-border-bottom u-pv-small">
                                <div class="col-sm-9">
                                    <div class="o-media u-mb-xsmall">
                                        <div class="o-media__img u-mr-xsmall">
                                            <img src="img/medal2.svg" alt="Lake King Badge">
                                        </div>

                                        <div class="o-media__body">
                                                    <span class="u-text-mute u-text-small u-text-uppercase">
                                                        Secret Award
                                                    </span>
                                            <p>Something really secret here...</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                            <span class="u-text-mute u-text-xsmall u-mb-xsmall u-text-uppercase">
                                                Progress
                                            </span>

                                    <div class="c-progress c-progress--warning c-progress--small">
                                        <div class="c-progress__bar" style="width:35%;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row o-line u-border-bottom u-pv-small">
                                <div class="col-sm-9">
                                    <div class="o-media u-mb-xsmall">
                                        <div class="o-media__img u-mr-xsmall">
                                            <img src="img/medal3.svg" alt="Lake King Badge">
                                        </div>

                                        <div class="o-media__body">
                                                    <span class="u-text-mute u-text-small u-text-uppercase">
                                                        Secret Award
                                                    </span>
                                            <p>Something really secret here...</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <a href="#" class="c-btn c-btn--info c-btn--fullwidth">
                                        Share
                                    </a>
                                </div>
                            </div>

                            <div class="row o-line u-border-bottom u-pv-small">
                                <div class="col-sm-9">
                                    <div class="o-media u-mb-xsmall">
                                        <div class="o-media__img u-mr-xsmall">
                                            <img src="img/medal4.svg" alt="Lake King Badge">
                                        </div>

                                        <div class="o-media__body">
                                                    <span class="u-text-mute u-text-small u-text-uppercase">
                                                        Secret Award
                                                    </span>
                                            <p>Something really secret here...</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <a href="#" class="c-btn c-btn--info c-btn--fullwidth">
                                        Share
                                    </a>
                                </div>
                            </div>

                            <div class="row o-line u-border-bottom u-pv-small">
                                <div class="col-sm-9">
                                    <div class="o-media u-mb-xsmall">
                                        <div class="o-media__img u-mr-xsmall">
                                            <img src="img/medal5.svg" alt="Lake King Badge">
                                        </div>

                                        <div class="o-media__body">
                                                    <span class="u-text-mute u-text-small u-text-uppercase">
                                                        Secret Award
                                                    </span>
                                            <p>Something really secret here...</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <a href="#" class="c-btn c-btn--info c-btn--fullwidth">
                                        Share
                                    </a>
                                </div>
                            </div>

                            <div class="row o-line u-border-bottom u-pv-small">
                                <div class="col-sm-9">
                                    <div class="o-media u-mb-xsmall">
                                        <div class="o-media__img u-mr-xsmall">
                                            <img src="img/medal2.svg" alt="Lake King Badge">
                                        </div>

                                        <div class="o-media__body">
                                                    <span class="u-text-mute u-text-small u-text-uppercase">
                                                        Secret Award
                                                    </span>
                                            <p>Something really secret here...</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                            <span class="u-text-mute u-text-xsmall u-mb-xsmall u-text-uppercase">
                                                Progress
                                            </span>

                                    <div class="c-progress c-progress--warning c-progress--small">
                                        <div class="c-progress__bar" style="width:75%;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row o-line u-border-bottom u-pv-small">
                                <div class="col-sm-9">
                                    <div class="o-media u-mb-xsmall">
                                        <div class="o-media__img u-mr-xsmall">
                                            <img src="img/medal3.svg" alt="Lake King Badge">
                                        </div>

                                        <div class="o-media__body">
                                                    <span class="u-text-mute u-text-small u-text-uppercase">
                                                        Secret Award
                                                    </span>
                                            <p>Something really secret here...</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <a href="#" class="c-btn c-btn--info c-btn--fullwidth">
                                        Share
                                    </a>
                                </div>
                            </div>

                            <div class="row o-line u-border-bottom u-pv-small">
                                <div class="col-sm-9">
                                    <div class="o-media u-mb-xsmall">
                                        <div class="o-media__img u-mr-xsmall">
                                            <img src="img/medal1.svg" alt="Lake King Badge">
                                        </div>

                                        <div class="o-media__body">
                                                    <span class="u-text-mute u-text-small u-text-uppercase">
                                                        Secret Award
                                                    </span>
                                            <p>Something really secret here...</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <a href="#" class="c-btn c-btn--info c-btn--fullwidth">
                                        Share
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div><!-- // .c-modal__content -->
                </div><!-- // .c-modal__dialog -->
            </div><!-- // .c-modal -->
        </div>

        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--info" data-toggle="modal" data-target="#modal6">
                Settings Modal
            </button>

            <!-- Modal -->
            <div class="c-modal c-modal--large modal fade" id="modal6" tabindex="-1" role="dialog" aria-labelledby="modal6" data-backdrop="static">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="c-modal__content">

                        <div class="c-modal__header">
                            <h3 class="c-modal__title">Settings</h3>

                            <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-close"></i>
                                    </span>
                        </div>

                        <div class="c-modal__subheader u-pv-zero o-line">
                            <a href="#!" class="c-modal__subheader-tab is-active">System</a>
                            <a href="#!" class="c-modal__subheader-tab">Emails</a>
                            <a href="#!" class="c-modal__subheader-tab">Messanger</a>
                            <a href="#!" class="c-modal__subheader-tab">Phone</a>
                            <a href="#!" class="c-modal__subheader-tab">Other</a>
                        </div>

                        <div class="c-modal__body c-modal__body--maximized">
                            <div class="o-line u-border-bottom u-pb-small">
                                <p>Go to next request after reply</p>
                                <div class="c-switch is-active">
                                    <input class="c-switch__input" id="switch12" type="checkbox" checked="checked">
                                </div>
                            </div>

                            <div class="o-line u-border-bottom u-pv-small">
                                <p>Allow keyboard arrow for switching between requests</p>
                                <div class="c-switch is-active">
                                    <input class="c-switch__input" id="switch13" type="checkbox" checked="checked">
                                </div>
                            </div>

                            <div class="o-line u-border-bottom u-pv-small">
                                <p>Show input as first method of answer</p>
                                <div class="c-switch">
                                    <input class="c-switch__input" id="switch14" type="checkbox">
                                </div>
                            </div>

                            <div class="o-line u-border-bottom u-pv-small">
                                <p>Show Notes opened in default</p>
                                <div class="c-switch is-active">
                                    <input class="c-switch__input" id="switch15" type="checkbox" checked="checked">
                                </div>
                            </div>

                            <div class="o-line u-border-bottom u-pv-small">
                                <p>Connect Automatic Signature</p>
                                <div class="c-switch">
                                    <input class="c-switch__input" id="switch16" type="checkbox">
                                </div>
                            </div>

                            <div class="o-line u-border-bottom u-pv-small">
                                <p>Automatically erase all messages after delete (Skip Trash)</p>
                                <div class="c-switch">
                                    <input class="c-switch__input" id="switch17" type="checkbox">
                                </div>
                            </div>

                            <div class="o-line u-pt-small">
                                <p>Go to next request after reply</p>
                                <div class="c-switch">
                                    <input class="c-switch__input" id="switch18" type="checkbox">
                                </div>
                            </div>
                        </div>

                        <div class="c-modal__footer">
                            <button class="c-btn c-btn--secondary">Cancel</button>
                            <button class="c-btn c-btn--success">Send</button>
                        </div>

                    </div><!-- // .c-modal__content -->
                </div><!-- // .c-modal__dialog -->
            </div><!-- // .c-modal -->
        </div>
    </div>

    <div class="row u-mb-xlarge">
        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--success" data-toggle="modal" data-target="#modal7">
                Connect a New Service
            </button>

            <!-- Modal -->
            <div class="c-modal c-modal--xsmall modal fade" id="modal7" tabindex="-1" role="dialog" aria-labelledby="modal7" data-backdrop="static">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="c-modal__content">

                        <div class="c-modal__header">
                            <h3 class="c-modal__title">Connect New Service</h3>

                            <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-close"></i>
                                    </span>
                        </div>

                        <div class="c-modal__body">

                            <div class="c-field u-mb-xsmall">
                                <label class="c-field__label" for="select12">Account</label>

                                <!-- Select2 jquery plugin is used -->
                                <select class="c-select" id="select12">
                                    <option>PayPal</option>
                                    <option>Skrill</option>
                                    <option>TransferWise</option>
                                </select>
                            </div>

                            <div class="c-field u-mb-xsmall">
                                <label class="c-field__label" for="select12">User ID</label>

                                <input class="c-input" type="email" placeholder="adam@gamil.com">
                            </div>

                            <div class="c-field u-mb-small">
                                <label class="c-field__label" for="select12">User Password</label>

                                <input class="c-input" type="password" placeholder="Numbers, Captial & Small letters">
                            </div>

                            <div class="c-card u-bg-secondary u-p-small u-mb-small">
                                <span class="u-text-mute u-text-small">Credentials Security</span>
                                <p>Your external account credentials are stored securely and used for the sole purpose of retrieving relevant reports.</p>
                            </div>

                            <a class="c-btn c-btn--success c-btn--fullwidth" href="#">
                                Connect Service
                            </a>
                        </div>

                    </div><!-- // .c-modal__content -->
                </div><!-- // .c-modal__dialog -->
            </div><!-- // .c-modal -->
        </div>

        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--info" data-toggle="modal" data-target="#modal8">
                Setup New Project
            </button>

            <!-- Modal -->
            <div class="c-modal c-modal--small modal fade" id="modal8" tabindex="-1" role="dialog" aria-labelledby="modal8" data-backdrop="static">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="c-modal__content">

                        <div class="c-modal__header">
                            <h3 class="c-modal__title">Setup New Project</h3>

                            <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-close"></i>
                                    </span>
                        </div>

                        <div class="c-modal__body">
                            <form action="/file-upload" class="dropzone u-mb-small" id="modal-dropzone" style="height: 180px;">
                                <div class="dz-message" data-dz-message>
                                    <i class="dz-icon fa fa-cloud-upload"></i>
                                    <span>Drag a file here or browse for a file to upload.</span>
                                </div>

                                <div class="fallback">
                                    <input name="file" type="file" multiple>
                                </div>
                            </form>

                            <div class="c-field u-mb-xsmall">
                                <label class="c-field__label" for="input-project">Project Name</label>

                                <input type="text" class="c-input" id="input-project" placeholder="Twitter Background">
                            </div>

                            <div class="c-field u-mb-xsmall">
                                <!-- Select2 jquery plugin is used -->
                                <select class="c-select" id="select14">
                                    <option>Twitter</option>
                                    <option>Facebook</option>
                                    <option>Google</option>
                                </select>
                            </div>
                        </div>

                        <div class="c-modal__footer u-justify-center">
                            <a class="c-btn c-btn--success" href="#">Create Project</a>
                        </div>

                    </div><!-- // .c-modal__content -->
                </div><!-- // .c-modal__dialog -->
            </div><!-- // .c-modal -->
        </div>

        <div class="col-sm-4 u-mb-medium">
            <!-- Button trigger modal -->
            <button type="button" class="c-btn c-btn--info" data-toggle="modal" data-target="#modal9">
                Billing
            </button>

            <!-- Modal -->
            <div class="c-modal c-modal--large modal fade" id="modal9" tabindex="-1" role="dialog" aria-labelledby="modal9" data-backdrop="static">
                <div class="c-modal__dialog modal-dialog" role="document">
                    <div class="c-modal__content">
                        <div class="o-media c-card u-border-zero">
                            <!-- `font-size: 0` is a quick fix to remove weird spacing -->
                            <div class="o-media__img u-hidden-down@tablet" style="font-size: 0;">
                                <img src="img/billing.jpg" alt="Image">
                            </div>

                            <div class="o-media__body u-p-medium">
                                <div class="o-line u-align-items-start">
                                    <h3 class="u-mb-medium">Credit Card Details</h3>

                                    <span class="c-modal__close u-text-mute" data-dismiss="modal" aria-label="Close">
                                                <i class="u-opacity-medium fa fa-close"></i>
                                            </span>
                                </div>


                                <div class="c-field u-mb-xsmall">
                                    <div class="c-field has-icon-left">
                                                <span class="c-field__icon">
                                                    <i class="fa fa-user-o"></i>
                                                </span>
                                        <label class="c-field__label u-hidden-visually" for="input21">Email</label>
                                        <input class="c-input" id="input21" type="text" placeholder="Email">
                                    </div>
                                </div>

                                <div class="c-field u-mb-xsmall">
                                    <div class="c-field has-icon-left">
                                                <span class="c-field__icon">
                                                    <i class="fa fa-credit-card"></i>
                                                </span>
                                        <label class="c-field__label u-hidden-visually" for="input22">Card Number</label>
                                        <input class="c-input" id="input22" type="text" placeholder="Card Number">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="c-field">
                                            <div class="c-field has-icon-left">
                                                        <span class="c-field__icon">
                                                            <i class="fa fa-calendar-o"></i>
                                                        </span>
                                                <label class="c-field__label u-hidden-visually" for="input23">MM / YY</label>
                                                <input class="c-input" id="input23" type="text" placeholder="MM/YY">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="c-field u-mb-small">
                                            <div class="c-field has-icon-left">
                                                        <span class="c-field__icon">
                                                            <i class="fa fa-lock"></i>
                                                        </span>
                                                <label class="c-field__label u-hidden-visually" for="input24">CVC</label>
                                                <input class="c-input" id="input24" type="text" placeholder="CVC">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="c-btn c-btn--success c-btn--fullwidth u-mb-small" href="#">
                                    Pay $20.00
                                </a>

                                <span class="c-divider has-text u-mb-small">
                                            or
                                        </span>

                                <a class="c-btn c-btn--info c-btn--fullwidth u-flex u-align-items-center u-justify-center" href="#">
                                    Pay with <img class="u-ml-xsmall" src="img/paypal.svg" alt="PayPal">
                                </a>
                            </div>
                        </div>
                    </div><!-- // .c-modal__content -->
                </div><!-- // .c-modal__dialog -->
            </div><!-- // .c-modal -->
        </div>
    </div>

    <!-- ============================== ALERTS ================================= -->

    <div class="row u-mb-xlarge">
        <div class="col-lg-6 u-mb-medium">
            <h4 class="u-mb-medium">Alerts</h4>
            <div class="c-alert c-alert--success">
                <i class="c-alert__icon fa fa-check-circle"></i> Success! This is positive notification.
            </div>

            <div class="c-alert c-alert--info">
                <i class="c-alert__icon fa fa-info-circle"></i> All servers are now running smoothly again! Thanks.
            </div>

            <div class="c-alert c-alert--warning alert fade show">
                <i class="c-alert__icon fa fa-exclamation-circle"></i> Warning. Loading of this page taking a way too long.

                <button class="c-close" data-dismiss="alert" type="button">&times;</button>
            </div>

            <div class="c-alert c-alert--danger alert fade show">
                <i class="c-alert__icon fa fa-times-circle"></i> Error. Can’t connect to the platform.

                <button class="c-close" data-dismiss="alert" type="button">&times;</button>
            </div>
        </div>

        <div class="col-lg-6">
            <h4 class="u-mb-medium">Popovers</h4>

            <div class="row">
                <div class="col-6">
                    <a class="c-btn c-btn--secondary u-mb-medium" role="button" data-toggle="popover" data-placement="top" data-tigger="focus" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." tabindex="0">
                        Popover on top
                    </a>

                    <a class="c-btn c-btn--secondary u-mb-medium" role="button" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." tabindex="0">
                        Popover on right
                    </a>
                </div>
                <div class="col-6 u-mb-medium">
                    <a class="c-btn c-btn--secondary u-mb-medium" role="button" data-toggle="popover" data-placement="bottom" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." tabindex="0">
                        Popover on bottom
                    </a>

                    <a class="c-btn c-btn--secondary u-mb-medium" role="button" data-toggle="popover" data-placement="left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." tabindex="0">
                        Popover on left
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================== BADGES / TOOLTIPS ========================= -->

    <div class="row u-mb-xlarge">
        <div class="col-md-5 u-mb-medium">
            <h4 class="u-mb-medium">Badges</h4>
            <span class="c-badge c-badge--success">Badge</span>
            <span class="c-badge c-badge--info">Badge</span>
            <span class="c-badge c-badge--danger">Badge</span>
            <span class="c-badge c-badge--primary">Badge</span>
            <span class="c-badge c-badge--warning">Badge</span>
            <span class="c-badge c-badge--secondary">Badge</span>
        </div>

        <div class="col-md-7 u-mb-medium">
            <h4 class="u-mb-medium">Tooltips</h4>

            <div class="row">
                <div class="col">
                    <span class="c-badge c-badge--info c-tooltip c-tooltip--top" aria-label="This is a tooltip">Tooltip Top</span>
                </div>

                <div class="col">
                    <span class="c-badge c-badge--info c-tooltip c-tooltip--right" aria-label="This is a tooltip">Tooltip Right</span>
                </div>

                <div class="col">
                    <span class="c-badge c-badge--info c-tooltip c-tooltip--bottom" aria-label="This is a tooltip">Tooltip Bottom</span>
                </div>

                <div class="col">
                    <span class="c-badge c-badge--info c-tooltip c-tooltip--left" aria-label="This is a tooltip">Tooltip Left</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============== PAGINATIONS, BREADCRUMBS, NOTIFICATIONS ================ -->
    <h4 class="u-mb-medium">Paginations, Breadcrumbs, Notifications</h4>

    <div class="row u-mb-xlarge">
        <div class="col-md-4 u-mb-medium">


            <nav class="c-pagination u-justify-between">
                <a class="c-pagination__control" href="#">
                    <i class="fa fa-caret-left"></i>
                </a>

                <p class="c-pagination__counter">Page 2 of 3</p>

                <a class="c-pagination__control" href="#">
                    <i class="fa fa-caret-right"></i>
                </a>
            </nav>


            <nav class="c-pagination u-justify-center">
                <ul class="c-pagination__list">
                    <li class="c-pagination__item">
                        <a class="c-pagination__control" href="#">
                            <i class="fa fa-caret-left"></i>
                        </a>
                    </li>

                    <li class="c-pagination__item"><a class="c-pagination__link" href="#">2</a></li>
                    <li class="c-pagination__item"><a class="c-pagination__link" href="#">3</a></li>
                    <li class="c-pagination__item"><a class="c-pagination__link" href="#">4</a></li>
                    <li class="c-pagination__item"><a class="c-pagination__link" href="#">5</a></li>
                    <li class="c-pagination__item">
                        <a class="c-pagination__link is-active" href="#">4</a>
                    </li>
                    <li class="c-pagination__item"><a class="c-pagination__link" href="#">6</a></li>

                    <li class="c-pagination__item">
                        <a class="c-pagination__control" href="#">
                            <i class="fa fa-caret-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>

            <nav class="c-pagination u-justify-between">
                <a class="c-pagination__control u-text-dark" href="#">Previous</a>
                <a class="c-pagination__control u-text-dark" href="#">Next</a>
            </nav>

            <nav class="c-pagination u-justify-between">
                <a class="c-pagination__control u-text-dark" href="#">
                    <i class="fa fa-caret-left u-mr-xsmall"></i>Previous
                </a>
                <a class="c-pagination__control u-text-dark" href="#">
                    Next<i class="fa fa-caret-right u-ml-xsmall"></i>
                </a>
            </nav>

        </div>

        <div class="col-md-4 u-ml-auto u-mb-medium">
            <ol class="c-breadcrumb">
                <li class="c-breadcrumb__item"><a href="#">Home</a></li>
                <li class="c-breadcrumb__item"><a href="#">About</a></li>
                <li class="c-breadcrumb__item"><a href="#">Team</a></li>
                <li class="c-breadcrumb__item is-active">Adam</li>
            </ol>
        </div>

        <div class="col-md-3">
            <a class="c-notification u-mr-small" href="#">
                        <span class="c-notification__icon">
                            <i class="fa fa-user-o"></i>
                        </span>
                <span class="c-notification__number">4</span>
            </a>

            <a class="c-notification u-mr-small" href="#">
                        <span class="c-notification__icon">
                            <i class="fa fa-globe"></i>
                        </span>
                <span class="c-notification__number">3</span>
            </a>

            <a class="c-notification" href="#">
                        <span class="c-notification__icon">
                            <i class="fa fa-cube"></i>
                        </span>
                <span class="c-notification__number">5</span>
            </a>
        </div>
    </div>

    <!-- =========================== PROGRESS BARS ============================= -->

    <div class="row u-mb-xlarge">
        <div class="col-md-6">
            <h4 class="u-mb-medium">Progress Bars</h4>
            <div class="c-progress c-progress--info">
                <div class="c-progress__bar" style="width:45%;"></div>
            </div>

            <div class="c-progress c-progress--primary">
                <div class="c-progress__bar" style="width:60%;"></div>
            </div>

            <div class="c-progress c-progress--warning">
                <div class="c-progress__bar" style="width:35%;"></div>
            </div>

            <div class="c-progress c-progress--fancy">
                <div class="c-progress__bar" style="width:70%;"></div>
            </div>

            <div class="c-progress c-progress--success">
                <div class="c-progress__bar" style="width:20%;"></div>
            </div>

            <div class="c-progress c-progress--danger">
                <div class="c-progress__bar" style="width:45%;"></div>
            </div>

        </div>

        <div class="col-md-6">
            <h4 class="u-mb-medium">Tabs</h4>

            <div class="c-tabs">
                <ul class="c-tabs__list nav nav-tabs" id="myTab" role="tablist">
                    <li><a class="c-tabs__link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Home</a></li>

                    <li><a class="c-tabs__link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a></li>

                    <li><a class="c-tabs__link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</a></li>
                </ul>
                <div class="c-tabs__content tab-content" id="nav-tabContent">
                    <div class="c-tabs__pane active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque distinctio expedita, voluptatem ducimus quam fuga, vero atque, error laboriosam odio provident eveniet nemo reiciendis non optio, laborum enim ipsum dolorum!</div>

                    <div class="c-tabs__pane" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">ducimus quam fuga, vero atque, error laboriosam odio provident eveniet nemo reiciendis non optio, laborum enim ipsum dolorum, voluptatem ducimus quam fuga, vero atque, error laboriosam odio provident eveniet nemo!</div>

                    <div class="c-tabs__pane" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">optio, laborum enim ipsum dolorum, voluptatem ducimus quam fuga, vero atque, error laboriosam odio provident eveniet nemo reicienoptio, laborum enim ipsum dolorum, voluptatem ducimus quam fuga, vero atque, error laboriosam odio provident eveniet nemo reicien</div>
                </div>
            </div>

            <!--                     <div class="c-tabs">
                                    <ul class="c-tabs__list">
                                        <li class="c-tabs__item">
                                            <a class="c-tabs__link active" id="home-tab" data-toggle="tab" href="#a" role="tab" aria-controls="home" aria-selected="true">Tab 1</a>
                                        </li>
                                        <li class="c-tabs__item">
                                            <a class="c-tabs__link" id="profile-tab" data-toggle="tab" href="#b" role="tab" aria-controls="profile" aria-selected="false">Tab 2</a>
                                        </li>
                                        <li class="c-tabs__item">
                                            <a class="c-tabs__link" id="contact-tab" data-toggle="tab" href="#c" role="tab" aria-controls="contact" aria-selected="false">Tab 3</a>
                                        </li>
                                    </ul>
                                    <div class="c-tabs__content">
                                          <div class="c-tabs__pane active" id="a" role="tabpanel" aria-labelledby="home-tab">
                                              <p>Jason is a tech savy Architect and Entrepreneur, maintaining a keen interest in software and design tools, such as parametric modelling (Rhino/Grasshopper) and BIM, he graduated from San Francisco university.</p>
                                          </div>
                                        <div class="c-tabs__pane " id="b" role="tabpanel" aria-labelledby="profile-tab">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi animi saepe voluptatum quaerat, a, incidunt. Quibusdam expedita deserunt consequuntur assumenda rerum beatae, sed cum hic. Eaque tenetur facere quod doloremque.</p>
                                        </div>
                                        <div class="c-tabs__pane " id="c" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                    </div>
                                </div> -->

        </div>
    </div><!-- // .row -->

    <!-- ============================== TABLES ================================= -->

    <h4 class="u-mb-medium">Tables</h4>
    <div class="row u-mb-medium">
        <div class="col-sm-12">

            <div class="c-table-responsive@desktop">
                <table class="c-table c-table--zebra u-mb-small" id="datatable2">
                    <thead class="c-table__head">
                    <tr class="c-table__row">
                        <th class="c-table__cell c-table__cell--head">Project</th>
                        <th class="c-table__cell c-table__cell--head">Deadline</th>
                        <th class="c-table__cell c-table__cell--head">Leader + Team</th>
                        <th class="c-table__cell c-table__cell--head">Budget</th>
                        <th class="c-table__cell c-table__cell--head">Status</th>
                        <th class="c-table__cell c-table__cell--head">
                            <span class="u-hidden-visually">Actions</span>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="c-table__row c-table__row--danger">
                        <td class="c-table__cell">New Dashboard
                            <small class="u-block u-text-mute">Google</small>
                        </td>

                        <td class="c-table__cell">17th Oct, 17
                            <small class="u-block u-text-danger">Overdue</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar1-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Norman Hammond<small class="u-block u-text-mute">UK Design Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$4,670
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-info u-mr-xsmall"></i>Early Stages
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton10">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Landing Page
                            <small class="u-block u-text-mute">Airbnb</small>
                        </td>

                        <td class="c-table__cell">2nd Jan, 18
                            <small class="u-block u-text-mute">in 3 months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar2-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Joseph Mullins<small class="u-block u-text-mute">External Company</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$5,740
                            <small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-warning u-mr-xsmall"></i>QA
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton11">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Customer Care Interface
                            <small class="u-block u-text-mute">Uber</small>
                        </td>

                        <td class="c-table__cell">1st Apr, 18
                            <small class="u-block u-text-mute">in 5 months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar3-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Ina Higgins
                                    <small class="u-block u-text-mute">UX Warriors</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$4,000
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-primary u-mr-xsmall"></i>Waiting for Client
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton12" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton12">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row c-table__row--danger">
                        <td class="c-table__cell">UX Consulting
                            <small class="u-block u-text-mute">Tapdaq</small>
                        </td>

                        <td class="c-table__cell">23th Dec, 18
                            <small class="u-block u-text-danger">Overdue</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar4-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Stella Munoz
                                    <small class="u-block u-text-mute">Dribbble Researchers</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$2,500
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-success u-mr-xsmall"></i>Finishing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton40" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton40">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Mongo DB Integration
                            <small class="u-block u-text-mute">Twitter</small>
                        </td>

                        <td class="c-table__cell">10th Apr, 17
                            <small class="u-block u-text-mute">in 4 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar5-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Dylan Shelton
                                    <small class="u-block u-text-mute">SF Dev Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$800
                            <small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-info u-mr-xsmall"></i>Early Stages
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton70" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton70">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Small Design Help
                            <small class="u-block u-text-mute">NASA</small>
                        </td>

                        <td class="c-table__cell">1st Mar, 18
                            <small class="u-block u-text-mute">in 6 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar6-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Ellen Santiago
                                    <small class="u-block u-text-mute">UK Design Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$2,180
                            <small class="u-block u-text-danger">Delayed</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-success u-mr-xsmall"></i>Finishing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton50" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton50">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Subpages
                            <small class="u-block u-text-mute">Facebook</small>
                        </td>

                        <td class="c-table__cell">1st Jan, 18
                            <small class="u-block u-text-mute">in 2 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar7-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Maurice Briggs
                                    <small class="u-block u-text-mute">Moscow Office</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">
                            $4,670<small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-warning u-mr-xsmall"></i>Designing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton60" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton60">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="u-text-mute u-text-uppercase u-mb-small">Without Caption</p>
    <div class="row u-mb-medium">
        <div class="col-sm-12">

            <div class="c-table-responsive@desktop">
                <table class="c-table c-table--zebra u-mb-small">
                    <thead class="c-table__head">
                    <tr class="c-table__row">
                        <th class="c-table__cell c-table__cell--head">Project</th>
                        <th class="c-table__cell c-table__cell--head">Deadline</th>
                        <th class="c-table__cell c-table__cell--head">Leader + Team</th>
                        <th class="c-table__cell c-table__cell--head">Budget</th>
                        <th class="c-table__cell c-table__cell--head">Status</th>
                        <th class="c-table__cell c-table__cell--head">
                            <span class="u-hidden-visually">Actions</span>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="c-table__row c-table__row--danger">
                        <td class="c-table__cell">New Dashboard
                            <small class="u-block u-text-mute">Google</small>
                        </td>

                        <td class="c-table__cell">17th Oct, 17
                            <small class="u-block u-text-danger">Overdue</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar1-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Norman Hammond<small class="u-block u-text-mute">UK Design Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$4,670
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-info u-mr-xsmall"></i>Early Stages
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton21" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton21">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Landing Page
                            <small class="u-block u-text-mute">Airbnb</small>
                        </td>

                        <td class="c-table__cell">2nd Jan, 18
                            <small class="u-block u-text-mute">in 3 months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar2-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Joseph Mullins<small class="u-block u-text-mute">External Company</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$5,740
                            <small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-warning u-mr-xsmall"></i>QA
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton20" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton20">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Customer Care Interface
                            <small class="u-block u-text-mute">Uber</small>
                        </td>

                        <td class="c-table__cell">1st Apr, 18
                            <small class="u-block u-text-mute">in 5 months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar3-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Ina Higgins
                                    <small class="u-block u-text-mute">UX Warriors</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$4,000
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-primary u-mr-xsmall"></i>Waiting for Client
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton22" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton22">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row c-table__row--danger">
                        <td class="c-table__cell">UX Consulting
                            <small class="u-block u-text-mute">Tapdaq</small>
                        </td>

                        <td class="c-table__cell">23th Dec, 18
                            <small class="u-block u-text-danger">Overdue</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar4-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Stella Munoz
                                    <small class="u-block u-text-mute">Dribbble Researchers</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$2,500
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-success u-mr-xsmall"></i>Finishing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton23" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton23">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Mongo DB Integration
                            <small class="u-block u-text-mute">Twitter</small>
                        </td>

                        <td class="c-table__cell">10th Apr, 17
                            <small class="u-block u-text-mute">in 4 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar5-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Dylan Shelton
                                    <small class="u-block u-text-mute">SF Dev Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$800
                            <small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-info u-mr-xsmall"></i>Early Stages
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton24" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton24">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Small Design Help
                            <small class="u-block u-text-mute">NASA</small>
                        </td>

                        <td class="c-table__cell">1st Mar, 18
                            <small class="u-block u-text-mute">in 6 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar6-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Ellen Santiago
                                    <small class="u-block u-text-mute">UK Design Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$2,180
                            <small class="u-block u-text-danger">Delayed</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-success u-mr-xsmall"></i>Finishing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton25" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton25">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Subpages
                            <small class="u-block u-text-mute">Facebook</small>
                        </td>

                        <td class="c-table__cell">1st Jan, 18
                            <small class="u-block u-text-mute">in 2 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar7-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Maurice Briggs
                                    <small class="u-block u-text-mute">Moscow Office</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">
                            $4,670<small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-warning u-mr-xsmall"></i>Designing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton26" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton26">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="u-text-mute u-text-uppercase u-mb-small">With Caption</p>
    <div class="row u-mb-large">
        <div class="col-sm-12">
            <div class="c-table-responsive@desktop">
                <table class="c-table c-table--highlight u-mb-small">

                    <caption class="c-table__title">
                        Ongoing Projects <small>32 Projects</small>

                        <a class="c-table__title-action" href="#!">
                            <i class="fa fa-cloud-download"></i>
                        </a>
                    </caption>

                    <thead class="c-table__head c-table__head--slim">
                    <tr class="c-table__row">
                        <th class="c-table__cell c-table__cell--head">Project</th>
                        <th class="c-table__cell c-table__cell--head">Deadline</th>
                        <th class="c-table__cell c-table__cell--head">Leader + Team</th>
                        <th class="c-table__cell c-table__cell--head">Budget</th>
                        <th class="c-table__cell c-table__cell--head">Status</th>
                        <th class="c-table__cell c-table__cell--head">
                            <span class="u-hidden-visually">Actions</span>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="c-table__row c-table__row--danger">
                        <td class="c-table__cell">New Dashboard
                            <small class="u-block u-text-mute">Google</small>
                        </td>

                        <td class="c-table__cell">17th Oct, 17
                            <small class="u-block u-text-danger">Overdue</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar8-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Norman Hammond<small class="u-block u-text-mute">UK Design Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$4,670
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-info u-mr-xsmall"></i>Early Stages
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton27" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton27">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Landing Page
                            <small class="u-block u-text-mute">Airbnb</small>
                        </td>

                        <td class="c-table__cell">2nd Jan, 18
                            <small class="u-block u-text-mute">in 3 months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar1-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Joseph Mullins<small class="u-block u-text-mute">External Company</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$5,740
                            <small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-warning u-mr-xsmall"></i>QA
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton28" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton28">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Customer Care Interface
                            <small class="u-block u-text-mute">Uber</small>
                        </td>

                        <td class="c-table__cell">1st Apr, 18
                            <small class="u-block u-text-mute">in 5 months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar2-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Ina Higgins
                                    <small class="u-block u-text-mute">UX Warriors</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$4,000
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-primary u-mr-xsmall"></i>Waiting for Client
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton29" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton29">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row c-table__row--danger">
                        <td class="c-table__cell">UX Consulting
                            <small class="u-block u-text-mute">Tapdaq</small>
                        </td>

                        <td class="c-table__cell">23th Dec, 18
                            <small class="u-block u-text-danger">Overdue</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar3-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Stella Munoz
                                    <small class="u-block u-text-mute">Dribbble Researchers</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$2,500
                            <small class="u-block u-text-mute">Paid</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-success u-mr-xsmall"></i>Finishing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton30" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton30">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Mongo DB Integration
                            <small class="u-block u-text-mute">Twitter</small>
                        </td>

                        <td class="c-table__cell">10th Apr, 17
                            <small class="u-block u-text-mute">in 4 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar4-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Dylan Shelton
                                    <small class="u-block u-text-mute">SF Dev Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$800
                            <small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-info u-mr-xsmall"></i>Early Stages
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton31" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton31">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Small Design Help
                            <small class="u-block u-text-mute">NASA</small>
                        </td>

                        <td class="c-table__cell">1st Mar, 18
                            <small class="u-block u-text-mute">in 6 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar5-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Ellen Santiago
                                    <small class="u-block u-text-mute">UK Design Team</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">$2,180
                            <small class="u-block u-text-danger">Delayed</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-success u-mr-xsmall"></i>Finishing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton32" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton32">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="c-table__row">
                        <td class="c-table__cell">Subpages
                            <small class="u-block u-text-mute">Facebook</small>
                        </td>

                        <td class="c-table__cell">1st Jan, 18
                            <small class="u-block u-text-mute">in 2 Months</small>
                        </td>

                        <td class="c-table__cell">
                            <div class="o-media">
                                <div class="o-media__img u-mr-xsmall">
                                    <div class="c-avatar c-avatar--xsmall">
                                        <img class="c-avatar__img" src="img/avatar6-72.jpg" alt="Adam's Face">
                                    </div>
                                </div>
                                <div class="o-media__body">
                                    Maurice Briggs
                                    <small class="u-block u-text-mute">Moscow Office</small>
                                </div>
                            </div>
                        </td>

                        <td class="c-table__cell">
                            $4,670<small class="u-block u-text-mute">Invoice Sent</small>
                        </td>

                        <td class="c-table__cell">
                            <i class="fa fa-circle-o u-color-warning u-mr-xsmall"></i>Designing
                        </td>

                        <td class="c-table__cell u-text-right">
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton33" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton33">
                                    <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="u-text-mute u-text-uppercase u-mb-small">Sortable Tables</p>
    <div class="row u-mb-large">
        <div class="col-12">
            <div class="c-table-responsive@desktop">
                <table class="c-table" id="datatable">
                    <caption class="c-table__title">
                        Sortable Tables <small>Powered by DataTables</small>
                    </caption>

                    <thead class="c-table__head c-table__head--slim">
                    <tr class="c-table__row">
                        <th class="c-table__cell c-table__cell--head no-sort">Project</th>
                        <th class="c-table__cell c-table__cell--head">Deadline</th>
                        <th class="c-table__cell c-table__cell--head no-sort">Leader + Team</th>
                        <th class="c-table__cell c-table__cell--head">Budget</th>
                        <th class="c-table__cell c-table__cell--head no-sort">Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="c-table__row">
                        <td class="c-table__cell">New Dashboard</td>
                        <td class="c-table__cell">17th Oct, 17</td>
                        <td class="c-table__cell">Mahmoud</td>
                        <td class="c-table__cell">$4,670</td>
                        <td class="c-table__cell">Finishing</td>
                    </tr>
                    <tr class="c-table__row">
                        <td class="c-table__cell">landing Page</td>
                        <td class="c-table__cell">23th Dec, 18</td>
                        <td class="c-table__cell">Norman Hammond</td>
                        <td class="c-table__cell">$5,740</td>
                        <td class="c-table__cell">Early Stages</td>
                    </tr>
                    <tr class="c-table__row">
                        <td class="c-table__cell">Customer Care Interface</td>
                        <td class="c-table__cell">2nd Jan, 18</td>
                        <td class="c-table__cell">Joseph Mullins</td>
                        <td class="c-table__cell">$4,000</td>
                        <td class="c-table__cell">QA</td>
                    </tr>
                    <tr class="c-table__row">
                        <td class="c-table__cell">UX Consulting</td>
                        <td class="c-table__cell">1st Apr, 18</td>
                        <td class="c-table__cell">Ina Higgins</td>
                        <td class="c-table__cell">$2,500</td>
                        <td class="c-table__cell">Waiting for Client</td>
                    </tr>
                    <tr class="c-table__row">
                        <td class="c-table__cell">Mongo DB Integration</td>
                        <td class="c-table__cell">23th Dec, 18</td>
                        <td class="c-table__cell">Stella Munoz</td>
                        <td class="c-table__cell">$800</td>
                        <td class="c-table__cell">Finishing</td>
                    </tr>
                    <tr class="c-table__row">
                        <td class="c-table__cell">Small Design Help</td>
                        <td class="c-table__cell">10th Apr, 17</td>
                        <td class="c-table__cell">Dylan Shelton</td>
                        <td class="c-table__cell">$2,180</td>
                        <td class="c-table__cell">Waiting for Client</td>
                    </tr>
                    <tr class="c-table__row">
                        <td class="c-table__cell">Subpages</td>
                        <td class="c-table__cell">1st Mar, 18</td>
                        <td class="c-table__cell">Ellen Santiago</td>
                        <td class="c-table__cell">$4,670</td>
                        <td class="c-table__cell">QA</td>
                    </tr>
                    <tr class="c-table__row">
                        <td class="c-table__cell">Analytics</td>
                        <td class="c-table__cell">1st Jan, 18</td>
                        <td class="c-table__cell">Maurice Briggs</td>
                        <td class="c-table__cell">$1350</td>
                        <td class="c-table__cell">Early Stages</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- =============================== ICONS ================================= -->

    <h4>ICONS</h4>
    <p class="u-text-mute u-mb-small">Dashboard relies on FontAwesome Icons, here is a list of the used icons.</p>

    <div class="c-table-responsive@tablet u-mb-xlarge">
        <table class="c-table">
            <tbody>
            <tr class="c-table__row">
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-search">
                                    <i class="fa fa-search"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-heart">
                                    <i class="fa fa-heart"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-bullhorn">
                                    <i class="fa fa-bullhorn"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-newspaper-o">
                                    <i class="fa fa-newspaper-o"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-diamond">
                                    <i class="fa fa-diamond"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-cube">
                                    <i class="fa fa-cube"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-globe">
                                    <i class="fa fa-globe"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-close">
                                    <i class="fa fa-close"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-plus">
                                    <i class="fa fa-plus"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-calendar">
                                    <i class="fa fa-calendar"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-envelope-o">
                                    <i class="fa fa-envelope-o"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-angle-up">
                                    <i class="fa fa-angle-up"></i>
                                </span>
                </td>
            </tr>

            <tr class="c-table__row">
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-times">
                                    <i class="fa fa-times"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-check">
                                    <i class="fa fa-check"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-circle-o">
                                    <i class="fa fa-circle-o"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-envelope-o">
                                    <i class="fa fa-envelope-o"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-caret-right">
                                    <i class="fa fa-caret-right"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-caret-left">
                                    <i class="fa fa-caret-left"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-cloud-download">
                                    <i class="fa fa-cloud-download"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-user-o">
                                    <i class="fa fa-user-o"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-exclamation-triangle">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-trash-o">
                                    <i class="fa fa-trash-o"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-times-circle">
                                    <i class="fa fa-times-circle"></i>
                                </span>
                </td>
                <td class="c-table__cell u-h3 u-border-right u-p-small u-text-center">
                                <span class="c-tooltip c-tooltip--top" aria-label="fa-angle-down">
                                    <i class="fa fa-angle-down"></i>
                                </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</div><!-- // .container -->

@endsection