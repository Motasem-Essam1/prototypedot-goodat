<div class="modal fade" id="upgradeMessageDialog" tabindex="-1" aria-labelledby="upgradeMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title" id="upgradeMessageModalLabel">Upgrade Message</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-upgrade-message">
                    <div class="text">Please upgrade your package, so you can contact with the service provider</div>
                    <div class="upgrade-message-action mt-3 text-right">
                        <a class="btn btn-bg d-inline-flex" href="{{ route('account.subscription') }}">Upgrade Package</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
