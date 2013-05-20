{* Twig

{# Widgets #}

{% block form_widget %}
{% spaceless %}
{% if compound %}
{{ block('form_widget_compound') }}
{% else %}
{{ block('form_widget_simple') }}
{% endif %}
{% endspaceless %}
{% endblock form_widget %}

{% block form_widget_simple %}
{% spaceless %}
{% set type = type|default('text') %}
<input type="{{ type }}" {{ block('widget_attributes') }} {% if value is not empty %}value="{{ value }}" {% endif %}/>
{% endspaceless %}
{% endblock form_widget_simple %}

{% block form_widget_compound %}
{% spaceless %}
<div {{ block('widget_container_attributes') }}>
{% if form.parent is empty %}
{{ form_errors(form) }}
{% endif %}
{{ block('form_rows') }}
{{ form_rest(form) }}
</div>
{% endspaceless %}
{% endblock form_widget_compound %}

{% block collection_widget %}
{% spaceless %}
{% if prototype is defined %}
{% set attr = attr|merge({'data-prototype': form_row(prototype) }) %}
{% endif %}
{{ block('form_widget') }}
{% endspaceless %}
{% endblock collection_widget %}

{% block textarea_widget %}
{% spaceless %}
<textarea {{ block('widget_attributes') }}>{{ value }}</textarea>
{% endspaceless %}
{% endblock textarea_widget %}

{% block choice_widget %}
{% spaceless %}
{% if expanded %}
{{ block('choice_widget_expanded') }}
{% else %}
{{ block('choice_widget_collapsed') }}
{% endif %}
{% endspaceless %}
{% endblock choice_widget %}

{% block choice_widget_expanded %}
{% spaceless %}
<div {{ block('widget_container_attributes') }}>
{% for child in form %}
{{ form_widget(child) }}
{{ form_label(child) }}
{% endfor %}
</div>
{% endspaceless %}
{% endblock choice_widget_expanded %}

{% block choice_widget_collapsed %}
{% spaceless %}
<select {{ block('widget_attributes') }}{% if multiple %} multiple="multiple"{% endif %}>
{% if empty_value is not none %}
<option {% if required %} disabled="disabled"{% if value is empty %} selected="selected"{% endif %}{% else %} value=""{% endif %}>{{ empty_value|trans({}, translation_domain) }}</option>
{% endif %}
{% if preferred_choices|length > 0 %}
{% set options = preferred_choices %}
{{ block('choice_widget_options') }}
{% if choices|length > 0 and separator is not none %}
<option disabled="disabled">{{ separator }}</option>
{% endif %}
{% endif %}
{% set options = choices %}
{{ block('choice_widget_options') }}
</select>
{% endspaceless %}
{% endblock choice_widget_collapsed %}

{% block choice_widget_options %}
{% spaceless %}
{% for group_label, choice in options %}
{% if choice is iterable %}
<optgroup label="{{ group_label|trans({}, translation_domain) }}">
{% set options = choice %}
{{ block('choice_widget_options') }}
</optgroup>
{% else %}
<option value="{{ choice.value }}"{% if choice is selectedchoice(value) %} selected="selected"{% endif %}>{{ choice.label|trans({}, translation_domain) }}</option>
{% endif %}
{% endfor %}
{% endspaceless %}
{% endblock choice_widget_options %}

{% block checkbox_widget %}
{% spaceless %}
<input type="checkbox" {{ block('widget_attributes') }}{% if value is defined %} value="{{ value }}"{% endif %}{% if checked %} checked="checked"{% endif %} />
{% endspaceless %}
{% endblock checkbox_widget %}

{% block radio_widget %}
{% spaceless %}
<input type="radio" {{ block('widget_attributes') }}{% if value is defined %} value="{{ value }}"{% endif %}{% if checked %} checked="checked"{% endif %} />
{% endspaceless %}
{% endblock radio_widget %}

{% block datetime_widget %}
{% spaceless %}
{% if widget == 'single_text' %}
{{ block('form_widget_simple') }}
{% else %}
<div {{ block('widget_container_attributes') }}>
{{ form_errors(form.date) }}
{{ form_errors(form.time) }}
{{ form_widget(form.date) }}
{{ form_widget(form.time) }}
</div>
{% endif %}
{% endspaceless %}
{% endblock datetime_widget %}

{% block date_widget %}
{% spaceless %}
{% if widget == 'single_text' %}
{{ block('form_widget_simple') }}
{% else %}
<div {{ block('widget_container_attributes') }}>
{{ date_pattern|replace({
'{{ year }}':  form_widget(form.year),
'{{ month }}': form_widget(form.month),
'{{ day }}':   form_widget(form.day),
})|raw }}
</div>
{% endif %}
{% endspaceless %}
{% endblock date_widget %}

{% block time_widget %}
{% spaceless %}
{% if widget == 'single_text' %}
{{ block('form_widget_simple') }}
{% else %}
{% set vars = widget == 'text' ? { 'attr': { 'size': 1 }} : {} %}
<div {{ block('widget_container_attributes') }}>
{{ form_widget(form.hour, vars) }}{% if with_minutes %}:{{ form_widget(form.minute, vars) }}{% endif %}{% if with_seconds %}:{{ form_widget(form.second, vars) }}{% endif %}
</div>
{% endif %}
{% endspaceless %}
{% endblock time_widget %}

{% block number_widget %}
{% spaceless %}
{# type="number" doesn't work with floats #}
    {% set type = type|default('text') %}
    {{ block('form_widget_simple') }}
{% endspaceless %}
{% endblock number_widget %}

{% block integer_widget %}
{% spaceless %}
    {% set type = type|default('number') %}
    {{ block('form_widget_simple') }}
{% endspaceless %}
{% endblock integer_widget %}

{% block money_widget %}
{% spaceless %}
    {{ money_pattern|replace({ '{{ widget }}': block('form_widget_simple') })|raw }}
{% endspaceless %}
{% endblock money_widget %}

{% block url_widget %}
{% spaceless %}
{% set type = type|default('url') %}
{{ block('form_widget_simple') }}
{% endspaceless %}
{% endblock url_widget %}

{% block search_widget %}
{% spaceless %}
{% set type = type|default('search') %}
{{ block('form_widget_simple') }}
{% endspaceless %}
{% endblock search_widget %}

{% block percent_widget %}
{% spaceless %}
{% set type = type|default('text') %}
{{ block('form_widget_simple') }} %
{% endspaceless %}
{% endblock percent_widget %}

{% block password_widget %}
{% spaceless %}
{% set type = type|default('password') %}
{{ block('form_widget_simple') }}
{% endspaceless %}
{% endblock password_widget %}

{% block hidden_widget %}
{% spaceless %}
{% set type = type|default('hidden') %}
{{ block('form_widget_simple') }}
{% endspaceless %}
{% endblock hidden_widget %}

{% block email_widget %}
{% spaceless %}
{% set type = type|default('email') %}
{{ block('form_widget_simple') }}
{% endspaceless %}
{% endblock email_widget %}

{% block button_widget %}
{% spaceless %}
{% if label is empty %}
{% set label = name|humanize %}
{% endif %}
<button type="{{ type|default('button') }}" {{ block('button_attributes') }}>{{ label|trans({}, translation_domain) }}</button>
{% endspaceless %}
{% endblock button_widget %}

{% block submit_widget %}
{% spaceless %}
{% set type = type|default('submit') %}
{{ block('button_widget') }}
{% endspaceless %}
{% endblock submit_widget %}

{% block reset_widget %}
{% spaceless %}
{% set type = type|default('reset') %}
{{ block('button_widget') }}
{% endspaceless %}
{% endblock reset_widget %}

{# Labels #}

{% block form_label %}
{% spaceless %}
{% if label is not sameas(false) %}
{% if not compound %}
{% set label_attr = label_attr|merge({'for': id}) %}
{% endif %}
{% if required %}
{% set label_attr = label_attr|merge({'class': (label_attr.class|default('') ~ ' required')|trim}) %}
{% endif %}
{% if label is empty %}
{% set label = name|humanize %}
{% endif %}
<label{% for attrname, attrvalue in label_attr %} {{ attrname }}="{{ attrvalue }}"{% endfor %}>{{ label|trans({}, translation_domain) }}</label>
{% endif %}
{% endspaceless %}
{% endblock form_label %}

{% block button_label %}{% endblock %}

{# Rows #}

{% block repeated_row %}
{% spaceless %}
{#
No need to render the errors here, as all errors are mapped
to the first child (see RepeatedTypeValidatorExtension).
#}
{{ block('form_rows') }}
{% endspaceless %}
{% endblock repeated_row %}

{% block form_row %}
{% spaceless %}
<div>
{{ form_label(form) }}
{{ form_errors(form) }}
{{ form_widget(form) }}
</div>
{% endspaceless %}
{% endblock form_row %}

{% block button_row %}
{% spaceless %}
<div>
{{ form_widget(form) }}
</div>
{% endspaceless %}
{% endblock button_row %}

{% block hidden_row %}
{{ form_widget(form) }}
{% endblock hidden_row %}

{# Misc #}

{% block form %}
{% spaceless %}
{{ form_start(form) }}
{{ form_widget(form) }}
{{ form_end(form) }}
{% endspaceless %}
{% endblock form %}

{% block form_start %}
{% spaceless %}
{% set method = method|upper %}
{% if method in ["GET", "POST"] %}
{% set form_method = method %}
{% else %}
{% set form_method = "POST" %}
{% endif %}
<form method="{{ form_method|lower }}" action="{{ action }}"{% for attrname, attrvalue in attr %} {{ attrname }}="{{ attrvalue }}"{% endfor %}{% if multipart %} enctype="multipart/form-data"{% endif %}>
{% if form_method != method %}
<input type="hidden" name="_method" value="{{ method }}" />
{% endif %}
{% endspaceless %}
{% endblock form_start %}

{% block form_end %}
{% spaceless %}
{% if not render_rest is defined or render_rest %}
{{ form_rest(form) }}
{% endif %}
</form>
{% endspaceless %}
{% endblock form_end %}

{% block form_enctype %}
{% spaceless %}
{% if multipart %}enctype="multipart/form-data"{% endif %}
{% endspaceless %}
{% endblock form_enctype %}

{% block form_errors %}
{% spaceless %}
{% if errors|length > 0 %}
<ul>
{% for error in errors %}
<li>{{ error.message }}</li>
{% endfor %}
</ul>
{% endif %}
{% endspaceless %}
{% endblock form_errors %}

{% block form_rest %}
{% spaceless %}
{% for child in form %}
{% if not child.rendered %}
{{ form_row(child) }}
{% endif %}
{% endfor %}
{% endspaceless %}
{% endblock form_rest %}

{# Support #}

{% block form_rows %}
{% spaceless %}
{% for child in form %}
{{ form_row(child) }}
{% endfor %}
{% endspaceless %}
{% endblock form_rows %}

{% block widget_attributes %}
{% spaceless %}
id="{{ id }}" name="{{ full_name }}"{% if read_only %} readonly="readonly"{% endif %}{% if disabled %} disabled="disabled"{% endif %}{% if required %} required="required"{% endif %}{% if max_length %} maxlength="{{ max_length }}"{% endif %}{% if pattern %} pattern="{{ pattern }}"{% endif %}
{% for attrname, attrvalue in attr %}{% if attrname in ['placeholder', 'title'] %}{{ attrname }}="{{ attrvalue|trans({}, translation_domain) }}" {% else %}{{ attrname }}="{{ attrvalue }}" {% endif %}{% endfor %}
{% endspaceless %}
{% endblock widget_attributes %}

{% block widget_container_attributes %}
{% spaceless %}
{% if id is not empty %}id="{{ id }}" {% endif %}
{% for attrname, attrvalue in attr %}{{ attrname }}="{{ attrvalue }}" {% endfor %}
{% endspaceless %}
{% endblock widget_container_attributes %}

{% block button_attributes %}
{% spaceless %}
id="{{ id }}" name="{{ full_name }}"{% if disabled %} disabled="disabled"{% endif %}
{% for attrname, attrvalue in attr %}{{ attrname }}="{{ attrvalue }}" {% endfor %}
{% endspaceless %}
{% endblock button_attributes %}

*}

{* Widgets *}

{function form_widget}
    <div {call widget_container_attributes}>
        {call form_rows}
        {form_rest form=$form}
    </div>
{/function}

{function collection_widget}
    {if isset($prototype)}
        {*$attr = array_merge($attr|merge({'data-prototype': form_row($prototype) }) %*}
    {/if}
    {form_widget}
{/function}

{function textarea_widget}
    <textarea {call widget_attributes}>{$value}</textarea>
{/function}

{function widget_choice_options}
    {foreach $options as $choice=>$label}
        {if $label|_form_is_choice_group}
            <optgroup label="{$choice|trans}">
                {foreach $label as $nestedChoice=>$nestedLabel}
                    <option value="{$nestedChoice}"{if $form|_form_is_choice_selected:$nestedChoice} selected="selected"{/if}>{$nestedLabel|trans}</option>
                {/foreach}
            </optgroup>
        {else}
            <option value="{$choice}"{if $form|_form_is_choice_selected:$choice} selected="selected"{/if}>{$label|trans}</option>
        {/if}
    {/foreach}
{/function}

{function choice_widget}
    {if $expanded}
        <div {call widget_container_attributes}>
        {foreach $form as $child}
            {form_widget form=$child}
            {form_label form=$child}
        {/foreach}
        </div>
    {else}
    <select {call widget_attributes}{if $multiple} multiple="multiple"{/if}>
        {if !empty($empty_value)}
            <option value="">{$empty_value|trans}</option>
        {/if}
        {if count($preferred_choices) > 0}
            {$options=$preferred_choices}
            {call widget_choice_options}
            {if count($choices) > 0 and !empty($separator)}
                <option disabled="disabled">{$separator}</option>
            {/if}
        {/if}
        {$options=$choices}
        {call widget_choice_options}
    </select>
    {/if}

{/function}

{function checkbox_widget}
    <input type="checkbox" {call widget_attributes}{if isset($value)} value="{$value}"{/if}{if $checked} checked="checked"{/if} />
{/function}

{function radio_widget}
    <input type="radio" {call widget_attributes}{if isset($value)} value="{$value}"{/if}{if $checked} checked="checked"{/if} />
{/function}

{function datetime_widget}
    {if $widget == 'single_text'}
        {call form_widget}
    {else}
        <div {call widget_container_attributes}>
            {form_errors form=$form.date}
            {form_errors form=$form.time}
            {form_widget form=$form.date}
            {form_widget form=$form.time}
        </div>
    {/if}
{/function}

{function date_widget}
    {if widget == 'single_text'}
        {call form_widget}
    {else}
        {*<div {call widget_container_attributes}>
            {{ date_pattern|replace({
                '{{ year }}':  form_widget(form.year),
                '{{ month }}': form_widget(form.month),
                '{{ day }}':   form_widget(form.day),
            })|raw }}
        </div>*}
    {/if}
{/function}

{function time_widget}
    {if widget == 'single_text'}
        {call form_widget}
    {else}
        {call slacker_notice function="time_widget"}
        {*<div {call widget_container_attributes}>
            {{ form_widget(form.hour, { 'attr': { 'size': '1' } }) }}:{{ form_widget(form.minute, { 'attr': { 'size': '1' } }) }}{if with_seconds}:{{ form_widget(form.second, { 'attr': { 'size': '1' } }) }}{/if}
        </div>*}
    {/if}
{/function}

{function number_widget}
    {* type="number" doesn't work with floats *}
    {$type=$type|default:'text'}
    {call form_widget}
{/function}

{function integer_widget}
    {$type=$type|default:'number'}
    {call form_widget}
{/function}

{function money_widget}
    {call slacker_notice function="money_widget"}
    {*$_money_pattern.widget={call form_widget}}
    {$money_pattern=array_replace($money_pattern, $_money_pattern)*}
    {*{ money_pattern|replace({ '{{ widget }}': {call form_widget})|raw }*}
{/function}

{function url_widget}
    {$type=$type|default:'url'}
    {call form_widget}
{/function}

{function search_widget}
    {$type=$type|default:'search'}
    {call form_widget}
{/function}

{function percent_widget}
    {$type=$type|default:'text'}
    {call form_widget}
{/function}

{function form_widget}
    {$type=$type|default:'text'}
    <input type="{$typ}" {call widget_attributes} {if !empty($value)}value="{$value}" {/if}/>
{/function}

{function password_widget}
    {$type=$type|default:'password'}
    {call form_widget}
{/function}

{function hidden_widget}
    {$type=$type|default:'hidden'}
    {call form_widget}
{/function}

{function email_widget}
    {$type=$type|default:'email'}
    {call form_widget}
{/function}

{* Labels *}

{function generic_label}
    {if $required}
        {if !isset($attr.class) || empty($attr.class)}{$attr.class=''}{/if}
    {/if}
    <label{foreach $attr as $attrname=>$attrvalue} {$attrname}="{$attrvalue}"{/foreach}>{$label|trans}</label>
{/function}

{function form_label}
    {$_attr.for=$id}
    {$attr=array_merge($attr, $_attr)}
    {call generic_label}
{/function}

{function form_label}
    {call generic_label}
{/function}

{* Rows *}

{function repeated_row}
    {call form_rows}
{/function}

{function form_row}
    <div>
        {form_label form=$form label=$label|default:null}
        {form_errors form=$form}
        {form_widget form=$form}
    </div>
{/function}

{function hidden_row}
    {form_widget form=$form}
{/function}

{* Misc *}

{function form_enctype}
    {if $multipart}enctype="multipart/form-data"{/if}
{/function}

{function form_errors}
    {if errors|count > 0}
    <ul>
        {foreach $errors as $error}
            <li>{$error.messageTemplate|trans:$error.messageParameters: 'validators'}</li>
        {/foreach}
    </ul>
    {/if}
{/function}

{function form_rest}
    {foreach $form as $child}
        {if !$child.rendered}
            {form_row form=$child}
        {/if}
    {/foreach}
{/function}

{* Support *}

{function form_rows}
    {form_errors form=$form}
    {foreach $form as $child}
        {form_row form=$child}
    {/foreach}
{/function}

{function widget_attributes}
    id="{$id}" name="{$full_name}"{if $read_only} disabled="disabled"{/if}{if $required} required="required"{/if}{if $max_length} maxlength="{$max_length}"{/if}{if $pattern} pattern="{$pattern}"{/if}
    {foreach $attr as $attrname=>$attrvalue}{$attrname}="{$attrvalue}" {/foreach}
{/function}

{function widget_container_attributes}
    id="{$id}"
    {foreach $attr as $attrname=>$attrvalue}{$attrname}="{$attrvalue}" {/foreach}
{/function}

{function slacker_notice}
    <h1 style="color:#900;">Smarty function <code>$function</code> is unfinished. Sorry!</h1>
{/function}