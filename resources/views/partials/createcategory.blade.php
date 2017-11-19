<div class="row">
    <div class="modal fade" id="modal-confirm" role="dialog" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-sm" id="confirm">Yes</button>
                    <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">No</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<div class="row">
    <div class="modal fade" id="modal-comment" role="dialog" tabindex="-1" aria-labelledby="paymentConfirmLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
                    <h4 class="modal-title">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Please add a comment
                            <hr/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="POST" class="form-horizontal" role="form" id="form">
                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                <div class="modal-footer">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control" placeholder="Comment goes here..." name="comment" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info btn-sm" id="confirm">Submit</button>
                                    <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="modal fade" id="modal-calculator" role="dialog" tabindex="-1" aria-labelledby="paymentConfirmLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
                    <h4 class="modal-title">Calculator</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="POST" class="form-horizontal" role="form" id="form">
                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                <div class="modal-footer">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="outstanding-loan">Outstanding Loan</label>
                                            <input type="text" name="loan" id="outstanding-loan" class="form-control" value="" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="paid">Amount Paid</label>
                                            <input type="text" name="amount" id="paid" class="form-control" value="" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="ext">New EXT</label>
                                            <input type="text" name="new-ext" id="ext" class="form-control" value="" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="reduction">Loan Reduction</label>
                                            <input type="text" name="reduction" id="reduction" class="form-control" value="" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="extension-fee">Extension Fee</label>
                                            <input type="text" name="fee" id="extension-fee" class="form-control" value="" disabled>
                                        </div>
                                    </div>
                                    {{--<button type="submit" class="btn btn-info btn-sm" id="confirm">Submit</button>--}}
                                    <button type="button" class="btn btn-info btn-sm" data-dismiss="modal"><i class="icon-check"></i> Done</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>