<div class="modal fade" id="reviewPendingDialog" tabindex="-1" aria-labelledby="reviewPendingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title" id="reviewPendingModalLabel">Review Pending</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-upgrade-message modal-review-pending text-center">
                    <img class="img-fluid mrp-img" src="{{ asset('assets/img/review-pending.png') }}" />
                    <div class="title text-center">Please wait until approve your review, thanks for waiting.</div>
                    <div class="pending-message-action mt-3 text-center">
                        <a class="btn btn-bg d-inline-flex" href="{{ route('account.reviews') }}">My Reviews</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
