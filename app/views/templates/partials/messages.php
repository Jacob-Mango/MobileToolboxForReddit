{% if flash.global %}

    <div class="alert alert-success fade show" style="position: absolute; left: 0px; right: 0px;">
        <button type="button" class="close" data-dismiss="alert">×</button>

        {{ flash.global }}
    </div>
    
{% endif %}