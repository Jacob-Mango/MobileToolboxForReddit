{% extends 'templates/default.php' %}

{% block content %}
            
<form action="/remove" method="post">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">                    
            <button type="submit" class="btn btn-default ml-auto">Remove</button>
        </div>
    </nav>
    <div class="container" style="margin-top: 20px;">
        <div class="form-group">
            <label for="url">Reddit Post/Comment URL</label>
            <input type="text" class="form-control" id="url" name="url" value="{{ url }}">
        </div>
        <div class="form-group">
            <label>Reason:</label>
            {% for reason in reasons %}
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="" onclick="checked" data-index"{{ reason.index }}" data-message="{{ reason.message }}">{{ reason.header }}
                    </label>
                </div>
            {% endfor %}
            
            <div class="form-check" style="width: 100%;">
                <label class="form-check-label" style="width: 100%;">
                    <input type="checkbox" class="form-check-input" id="custom_checked" value="">Custom Reason
                </label>
                <textarea class="form-control" rows="3" id="cus_reason" style="width: 100%;"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="preview">Preview of Message:</label>
            <textarea class="form-control" rows="5" id="preview" onkeypress="return false;" name="preview"></textarea>
        </div>
    </div>
</form> 

<script>
    function setPreview(header, footer, reasons, custom) {
        var sub = "{{ sub }}";
        var type = "{{ type }}";
        var user = "{{ user }}";

        preview_text = "";
        if (header)
            preview_text = preview_text + header + "\n\n";
        if (custom)
            preview_text = preview_text + custom + "\n\n";
        if (footer)
            preview_text = preview_text + footer;


        preview_text = replaceAll(preview_text, "{author}", user);
        preview_text = replaceAll(preview_text, "{subreddit}", sub);
        preview_text = replaceAll(preview_text, "{kind}", type);

        $("#preview").val(preview_text);
    }

    $("#cus_reason").toggle('visibility');
    $("#cus_reason").toggle('display');
    var number_of_reasons = {{ number_of_reasons }};
    var preview_text = "";
    $("#custom_checked").change(function() {
        $("#cus_reason").toggle('visibility');
        $("#cus_reason").toggle('display');
    });
    setPreview("{{ header }}", "{{ footer }}", "", "");
    $("#cus_reason").on("input", function(e) {
        var custom_reason = $("#cus_reason").val();
        setPreview("{{ header }}", "{{ footer }}", "", custom_reason);
    });
</script>

{% endblock %}