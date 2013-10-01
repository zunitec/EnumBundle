EnumBundle
==========
Para configurar adicione um type no doctrine.
Vá em config (Arquivos importantes):

doctrine:
    dbal:
        ...
        types:
            enum: Zuni\EnumBundle\DBAL\Types\EnumType


Criar a classe da enum:

namespace Zuni\PessoaBundle\Enum;

use Zuni\EnumBundle\Model\AbstractEnumList;
use Zuni\EnumBundle\Model\AbstractEnumObject;

class TipoEnderecoEnum extends AbstractEnumList
{

    private static $instance;

    /**
     *
     * @var TipoEndereco 
     */
    public $PRINCIPAL;

    /**
     *
     * @var TipoEndereco 
     */
    public $ENTREGA;

    /**
     *
     * @var TipoEndereco 
     */
    public $COBRANCA;

    function __construct()
    {
        $this->PRINCIPAL = new TipoEndereco("P", "Principal");
        $this->ENTREGA = new TipoEndereco("E", "Entrega");
        $this->COBRANCA = new TipoEndereco("C", "Cobrança");
    }

    /**
     * 
     * @return TipoEnderecoEnum
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new TipoEnderecoEnum();
        }
        return self::$instance;
    }

}

class TipoEndereco extends AbstractEnumObject{}

No form type, passar a enumList no options. Ele vai mostrar a listagem padrão:

    $builder
          ->add('tipoEndereco', 'enum', array(
                "label" => 'endereco.tipoEndereco',
                "enumList" => TipoEnderecoEnum::getInstance()
            ));
            
            
Caso queria passar uma listagem diferente, use o choices:

    $builder
          ->add('tipoEndereco', 'enum', array(
                "label" => 'endereco.tipoEndereco',
                "enumList" => TipoEnderecoEnum::getInstance(),
              "choices" => \Zuni\PessoaBundle\Enum\RegimeTributarioEnum::getInstance()->getLista(),
            ));
            
Ao declarar a classe, use as anotações da enum, faça da seguinte forma:

use Zuni\EnumBundle\Annotation;

    /**
     * Endereco
     *
     * @Annotation\HasEnum
     */
    class Endereco

Ao declarar o atributo da enum na classe, faça da seguinte forma:

    /**
     * @var \Zuni\PessoaBundle\Enum\TipoEndereco
     * 
     * @Annotation\Enum(enumList="\Zuni\PessoaBundle\Enum\TipoEnderecoEnum")
     */
    private $tipoEndereco;
    
Acrescentar no form_widget:

{% block enum_widget %}
{% spaceless %}
    {% if expanded %}
        {{ block('choice_widget_expanded') }}
    {% else %}
        {{ block('enum_widget_collapsed') }}
    {% endif %}
{% endspaceless %}
{% endblock enum_widget %}

{% block enum_widget_collapsed %}
{% spaceless %}
    <select {{ block('widget_attributes') }}{% if multiple %} multiple="multiple"{% endif %}>
        {% if empty_value is not none %}
            <option value=""{% if required and value is empty %} selected="selected"{% endif %}>{{ empty_value|trans({}, translation_domain) }}</option>
        {% endif %}
        {% if preferred_choices|length > 0 %}
            {% set options = preferred_choices %}
            {{ block('choice_widget_options') }}
            {% if choices|length > 0 and separator is not none %}
                <option disabled="disabled">{{ separator }}</option>
            {% endif %}
        {% endif %}
                
        {% if choices %}
            {% set options = choices %}
            {{ block('choice_widget_options') }}
        {% else %}        
            {% set options = enumList.getLista() %}
                
            {{ block('enum_widget_options') }}
        {% endif %}        
                
    </select>
{% endspaceless %}
{% endblock enum_widget_collapsed %}


        
{% block enum_widget_options %}
{% spaceless %}
    {% for group_label, choice in options %}
        {% if choice is iterable %}
            <optgroup label="{{ group_label|trans({}, translation_domain) }}">
                {% set options = choice %}
                {{ block('enum_widget_options') }}
            </optgroup>
        {% else %}
            <option value="{{ choice.id }}" {% if value %}{% if choice.id == value %} selected="selected"{% endif %}{% endif %}>{{ choice|trans({}, translation_domain) }}</option>
        {% endif %}
    {% endfor %}
{% endspaceless %}
{% endblock enum_widget_options %}

Após isso tudo, deverá funcionar.