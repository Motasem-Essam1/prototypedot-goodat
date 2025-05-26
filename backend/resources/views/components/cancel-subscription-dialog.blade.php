<div class="modal fade" id="cancelSubscriptionDialog" tabindex="-1" aria-labelledby="cancelSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title" id="cancelSubscriptionModalLabel">Cancel Subscription</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-cancel-subscription">
                    <div class="cancel-subscription-view pt-2 pb-3 text-center">
                        <span class="icon-exclamation-mark icon"></span>
                    </div>
                    <div class="text mcs-text text-center">Are you sure to cancel current package ?</div>
                    <div class="upgrade-message-action mt-4 text-right d-flex justify-content-end">
                        <button type="button" class="btn button-default" data-bs-dismiss="modal">Close</button>
                        <a class="btn btn-bg ml-2" href="{{ route('subscription.cancel') }}">Confirm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
