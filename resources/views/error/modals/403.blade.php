<style>
    .error-icon{
        width: 50px;
    }
    .card{
        box-shadow: none;
    }
</style>

<div class="modal-body">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="card p-5 border-0">
        <h3 class="text-center">CRM <sub>V-1.0</sub></h3>
        <div class="card-body">
            <div class="mb-5 text-center">
                <img class="error-icon" src="{{asset('backend/images/icon/403.png')}}" alt="">
                <h1 class="error-header">403 | Unauthorized</h1>
            </div>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>



