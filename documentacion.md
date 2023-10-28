# Documentación de TusListas

Esta documentación tiene por objetivo servir de guia o intrucciones para el usuario.

- TusListas mantiene tus precios actualizados, como? Aumentos Generales, Aumento Dólar e Ingreso de Mercaderia. Todos los precios tienen su propia fecha. Fraccionado se actualiza a la vez que el producto original.
- TusListas organiza tu inventario de productos, como? Los productos se almacenan clasificados por Categoria, Fabricante y Proveedor. Una vez almacenados pueden buscarse de multiples formas.

Enumerar cada funcionalidad/vista:

- Buscador
- Ingreso de Mercaderia
- Descuentos y Duración
- Agregar nuevo Producto
- Ganancia
- Fraccionado
- Calculo de Precio de Venta
- Aumentos Generales
- Aumento en base al Dolar
- Registro de Aumentos
- Agenda
- Owner Tools y roles de usuario
- Agregar y eliminar miembros de la empresa
- Cambiar nombre de la empresa
- Estadisticas
- Responsive, en todos tus dispositivos

### Buscador

- Listado de todos los productos, pueden ser filtrados y ordenados para facilitar la busqueda del usuario. Tambien es donde el usuario puede eliminar un lote de productos (funcionalidades pendientes).
- Busqueda por nombre, una vez ingresados 3 caracteres el buscador realiza una busqueda en toda la base de datos. Imprime un listado de 6 productos debajo del campo de busqueda y permite al usuario escoger alguno. El producto seleccionado se imprime en pantalla como una tarjeta con sus datos.
- Busqueda por código, en caso de no encontrar un producto el usuario puede realizar la búsqueda por código. Si ingresa un código válido el buscador realiza una busqueda en la DB, si el código no se encuentra es porque el producto no existe.

**Colocar screenshots de cada elemento del buscador (los tabs) y cada vista. También hacer incapie en la tarjeta que representa el producto (normal y en oferta).**

### Ingreso de Mercaderia

El ingreso de mercaderia se basa en como se presentan las facturas, remitos o boletas de una distribuidora/proveedor. Al ingresar mercaderia en el comercio/empresa se pueden ingresar cada uno de los productos en esta sección. El formulario de ingreso de mercaderia cuenta con dos buscadores para evitar los productos duplicados, el usuario puede ingresar el código del producto o buscarlo en el mismo campo "nombre de producto" (los datos almacenados van a imprimirse en pantalla\).

#### Ingreso de productos existentes

Es útil ingresar cada pedido en esta vista ya que permite comprobar que el precio siga vigente y al mismo tiempo actualiza la fecha del mismo, de esta forma el usuario puede saber cuando fue la última vez que pagó ese precio por el producto.

#### Descuento y Duración

Al ingresar mercaderia se puede aplicar un descuento, normalmente las distribuidoras aplican descuentos temporales y varian en cada ingreso de mercaderia. En el campo Descuento se ingresa el porcentaje de descuento aplicado (40% por ejemplo) y en el campo Duración se ingresa la duración de este descuento expresada en semanas. Los descuentos se eliminan automaticamente al cumplirse la duración estipulada, para extender el descuento solo se debe volver a ingresar el producto en esta vista.

#### Pendientes

Para mantener el flujo de trabajo del usuario puede indicar que un producto es Pendiente cuando el mismo no se encuentra en la base de datos. De otra forma el usuario tendria que salir de esta vista y dirigirse a "Nuevo Producto" para luego añadirlo en Ingreso de Mercaderia. Marcando el producto como pendiente, TusListas permite almacenar temporalmente los datos del mismo (cantidad, nombre, precio de costo, descuento y duración). Los Pendientes no son almacenados en la base de datos de productos ni pueden ser buscador en el buscador. Los Pendientes se encuentran disponibles en la vista Nuevo Producto y pueden ser agregados uno a uno en la base de datos (esto es indispensable para poder asignarles una categoria, proveedor, fabricante, dolar y ganancia), tambien el usuario tiene la posibilidad de crear una versión fraccionada de este articulo.

#### Cotización del Dólar

En la vista Ingreso de Mercaderia se muestra la cotización del dólar que será aplicada a todos los productos ingresados, este valor corresponde al mas alto encontrado en la base de datos. De esta forma se actualiza en los productos ya existentes. Para modificar este valor solo hay que ingresar o modificar un producto aplicando valor de dólar mas alto que el actual.

### Nuevo Producto

En el formulario de Nuevo Producto el usuario puede ingresar cada producto o articulo de su inventario, aqui se genera automaticamente un código único que será asignado a este producto. Al mismo tiempo el usuario puede darle un nombre único, clasificarlo por categoria, proveedor y fabricante.

#### Dolar Hoy

En cada producto se puede ingresar la cotización del dolar al dia de la fecha, de esta forma cuando la cotización cambia brucamente se pueden actualizar todos los productos que hayan sido ingresados con un dólar que se encuentre desactualizado. El campo dólar se completa automaticamente con valor del dólar mas alto que se encuentre en la base de datos (cero en caso de no haber ningun producto), el usuario tambien puede ingresarlo manualmente.

#### Precio con IVA

El IVA aplicado a los precios es del 21%, si el usuario ingresa el valor sin IVA se suma este 21% y lo contrario si ingresa el valor con IVA. Luego se aplica la ganancia indicada para calcular el precio de venta.


#### Precio de Venta

El precio de venta es calculado a partir del precio de costo (con o sin IVA) y se multiplica por la ganancia ingresada por el usuario. Por ejemplo un producto de costo sin IVA $100 es multiplicado por 1.21 para sumar el IVA y luego por la ganancia (si fuece 1.8 o sea un 80% de ganancia): (100 x 1.21) x 1.8 = 217.8

Para evitar conflictos de centavos los precios de venta se redondean siempre hacia arriba en un número multiplo de 10. Tener en cuenta esto al momento de compara los precios de TusListas con los calculados a mano. Este redondeo tambien aplica a los descuentos.


#### Ganancia

El indice de ganancia es el porcentaje que se suma al valor de costo y que representa la ganancia del vendedor, por ejemplo un indice de ganancia de 1.1 representa un 10% sobre el valor original. Si el costo del producto con IVA es de $100 y se aplica una ganancia del 10% (1.1) el beneficio del vendedor son $10.

El usuario tiene 3 opciones para asignar una ganancia al producto: 

- Categoria, el producto va a tomar la ganancia aplicada a esta categoria.
- Proveedor, el producto va a tomar la ganancia aplicada a este proveedor.
- Personalizada, un indice personalizado para este producto. La desventaja de esta opción es que si desea cambiar la el indice de ganancia en el futuro debe hacerlo dentro de cada producto.


#### Fraccionado

La opción de fraccionado crea un nuevo producto como una versión "fraccionada" del primero o sea que comparte la mayoria de las caracteristicas del producto original pero tiene su propio código único y una ganancia extra. En muchos casos los comercios/empresas comercializan productos por unidad y tambien fracciones del mismo, para facilitar el ingreso y mantenimiento de estos precios existen los fraccionados.

Un producto fraccionado comparte nombre (se agrega "- FRACCIONADO" para diferenciarlos), Categoria, Proveedor, Fabricante, Coritzación Dólar, Precio Costo y Ganancia con el producto original. Si se modifica cualquiera de estas cualidades del producto original tambien serán modificados en el fraccionado.

Un producto fraccionado cuenta con cualidades propias:

- Unidad del Producto, este es el nombre que puede darse a la unidad. Por ejemplo si el producto fuece una caja de medicamentos la Unidad del Producto seria "blister", si el producto fuece una bolsa de Cereales podria dividirse en Kilogramos, etc. Este solo es el nombre que se utiliza para referirse a la fracción cuando es buscado el producto.
- Total de Unidades, el número por el cual se divide el producto original. Si el producto original tiene un Precio de Costo de 500 y el Total de Unidades es de 2, este precio seria 250.
- Ganancia Extra Fracción, cuando se fracciona un producto normalmente se pretende tener una ganancia extra. Puede interpretarse que el producto original (completo) tiene un valor mas bajo y el fraccionado tiene un extra de ganancia o un valor mas alto. Esta ganancia se suma al Precio de Venta calculado para el producto original. Por ejemplo, si el precio de costo con IVA de un producto es 200 y se aplica una ganancia de 1.5 (50%) el precio de Venta del articulo completo es de 300. Luego se genera una versión fraccionada del mismo con un Total de Unidades de 10 y una Ganancia Extra Fracción de 1.2 (20%), el Precio de Venta del producto fraccionado seria de 36. El Precio de Venta se divide por el Total de Unidades y se multiplica por Ganancia Extra Fracción.


### Aumentos Generales

Esta es una poderosa herramienta a la hora de aumentar un lote completo de productos, es uno de los motivos principales por los cuales se clasifican los productos segun su Categoria, Proveedor y Fabricante. Esta funcionalidad solo se encuentra disponible para el usuario Owner (el creador de la Empresa/Comercio) ya que no pueden revertirse estos aumentos.

El usuario puede seleccionar una Categoria, Proveedor o Fabricante y aplicar un porcentaje de aumento a todos los productos que pertenezcan al mismo. Por ejemplo, seleccionando una categoria y aplicando un aumento del 15% todos los precios de los productos que pertenezcan a esta categoria serán multiplicados por 15%. Lo mismo ocurre con Proveedores y Fabricantes, esto es muy útil cuando un Fabricante aplica un aumento lineal a todo su catalogo.

Dentro de la vista Registro Aumentos se almacena un registro de cada uno de estos Aumentos Generales (solo los últimos 50 registros). Estos aumentos no pueden ser revertidos y comprometen los precios de multiples productos, esta opción no siempre es la mejor ya que un error puede comprometer los precios de multiples articulos. Siempre es mas aconsejable actualizar un precio a la vez en Ingreso de Mercaderia o Modificando un producto en especifico.

La fecha de todos los precios modificados de esta forma será actualizada también.


#### Aumento en base al dólar

Otra potente herramienta de TusListas para permitir al usuario realizar el aumento de un lote completo de productos es Aumento en base al Dólar. Este es el motivo por el cual se asigna un elemento que corresponde a la cotización del dólar en cada uno de los productos, si el usuario utiliza correctamente este elemento, cada producto tiene asignada la cotización del dólar el dia en que fue ingresado este articulo.

En el campo de Dólar el usuario ingresa la cotización actual, al enviarlo se realiza una busqueda de todos los productos cuya cotización de  dólar sea inferior a la ingresada. Todos los productos encontrados en la base de datos son listados en esta vista y el usuario tiene la posibilidad de actualizarlos, esta actualización cambia el valor de cotización del dolar de cada uno de los productos listados y aumenta su precio de costo acorde a la diferencia entre el valor desactualizado y el actual.

Por ejemplo si el usuario ingresa una cotización del dolar de 500 y la busqueda devuelve 3 productos que tienen una cotización de 400, el usuario puede actualizar sus precios con un solo click. TusListas divide la cotización nueva y la desactualizada como 500 / 400, este indice (1.25 o 25%) es multiplicado por el precio de costo de cada producto generando un aumento general del 25% en este caso.

Al igual que otros Aumentos Generales, los aumentos en base al dolar también son registrados en Registro de Aumentos y tampoco pueden revertirse. Se debe tener precaución con esta herramienta ya que afecta a un lote completo de productos y puede comprometer el precio de los mismos. La fecha de todos los productos es actualizada al dia del aumento. Esta herramienta solo puede ser utilizada por un usuario con el rol de Owner (el creador de la empresa/comercio).

#### Registro de Aumentos

Los Aumentos Generales y Aumento en base al Dolar son registrados y listados en Registro de Aumentos, esta vista solo puede consultarla el usuario administrador de la empresa/comercio y lista un máximo de 50 registros (los mas actuales). Estos aumentos pueden ser consultados pero no revertidos.


### Agenda

La agenda permite al usuario almacenar y administrar los tres elementos que clasifican a sus productos, estos son Categorias, Proveedores y Fabricantes.

- Categoria: Es una forma de clasificar los articulos que comercializa el usuario, todos los comercios/empresas pueden clasificar sus articulos por algún tipo de categoria. Por ejemplo, "Nacionales", "Importados", "Bebidas", "Mesas", "Sillas" "Usados", "Nuevos", y un gran etcetera. Clasificar por Categoria es muy importante a la hora de buscar productos (listado de Todos los Productos), tambien para aplicar una ganancia a multiples productos diferentes y permite aplicar Aumentos Generales a todo un lote de productos. En cuanto a la ganancia por categoria, esto permite al usuario modificar el indice de ganancia (el dinero que espera ganar por la venta de ciertos articulos) de multiples articulos y sin riesgo. Este indice puede ser modificado en cualquier momento afectando a los Precios de Venta de los productos pero sin afectar el Precio de Costo ni la fecha de actualización del mismo. La ganancia por categoria permite al usuario hacer "pruebas generales" que afecten a multiples productos, si no funciona puede revertirlo en cualquier momento. Si el usuario no desea utilizar este tipo de clasificación sencillamente puede crear una categoria con un nombre generico como "Otros" y aplicar una ganancia de 1 (sin ganancia), de esta forma este elemento no afecta a sus productos pero también pierde las funcionalidades de clasificación y Aumentos Generales.
- Proveedor: Es otra forma de clasificar articulos, los proveedores son las distribuidoras o fabricas que abastecen al comercio/empresa del usuario. En cada uno el usuario puede ingresar los datos de contacto para disponer de ellos en cualquier momento, también puede aplicar un indice de ganancia. Al igual que Categorias, Proveedores puede ser utilizado para aplicar un indice de ganancia a un lote de articulos y modificarlo en cualquier momento. Si el usuario no desea utilizar este tipo de clasificación sencillamente puede crear un proveedor con nombre generico como "Otros" y aplicar una ganancia de 1 (sin ganancia), de esta forma este elemento no afecta a sus productos pero también pierde las funcionalidades de clasificación y Aumentos Generales.
- Fabricante: Es otra forma de clasificar articulos, permite al usuario almacenar los datos de contacto de los fabricantes de sus productos y disponer de los mismos en cualquier momento. También es muy útil a la hora de utilizar los Aumentos Generales. Si el usuario no desea utilizar este tipo de clasificación sencillamente puede crear un fabricante con nombre generico como "Otros" y aplicar una ganancia de 1 (sin ganancia), de esta forma este elemento no afecta a sus productos pero también pierde las funcionalidades de clasificación y Aumentos Generales. En caso de que un fabricante provea la mercaderia es conveniente crear tanto un Proveedor como un Fabricante aunque compartan la mayoria de la información, luego es indistinto cual se utiliza para el Aumento General de los articulos que provee.


### Herramientas de Administrador

Cuando un usuario crea una nueva Empresa/Comercio también esta creando la cuenta con el rol de administrador, el dueño de la empresa. Este usuario es diferente a los demás puesto que cuenta con herramientras propias de un administrador como son la posibilidad de invitar a otros usuarios a su empresa, cambiar el nombre de la empresa, eliminar usuarios de su empresa, visualizar las estadisticas y realizar Aumentos Generales.

Solo los usuarios con el rol de Administrador tienen a disposición el enlace "Admin" donde encuentran las herramientas de administración. La funcionalidad de Aumentos Generales esta bloqueada para todos los usuarios que no sean administrador. Es muy importante el rol de este usuario ya que puede realizar cambios que comprometen la integridad de datos de su empresa, como por ejemplo modificar el precio de costo de todos sus productos en Aumentos Generales o eliminar usuarios.

#### Invitar Usuario

Una vez verificada la cuenta del usuario administrador puede acceder a las herramientas de administrador y completar el campo "Invitar Usuario", de esta forma TusListas envia un email a la casilla de correo ingresada con una invitación a la app y la empresa. El usuario invitador recibe un enlace que le permite crear una cuenta relacionada únicamente a la empresa del administrador, este nuevo usuario puede acceder a todas las herramientas de TusListas excepto las antes mencionadas, también puede ver y modificar todos los productos de la empresa.

#### Listas y eliminar usuarios

El Administrador cuenta con un listado de los usuarios que pertenecen a su empresa y puede eliminar a cualquiera de ellos.

#### Estadisticas

Funcionalidad en desarrollo. Llegará pronto!

#### Cambiar nombre de la empresa

El usuario administrador puede cambiar el nombre de la empresa en cualquier momento, el resto de los usuarios solo verán este cambio despues de haber cerrado sesión una vez.

#### En todos tus dispositivos

TusListas funciona en móviles sin necesidad de "descargar una aplicación" y es ejecutada dentro de cualquier navegador que tenga instalado el usuario. Esto es muy útil para que el usuario utilice el buscador y consulte cualquer producto en cualquier momento pero dificulta utilizar herramientas como Ingreso de Mercaderia o visualizar tablas completas. La versión de escritorio de TusListas es mucho mas cómida a la hora de utilizar estas herramientas y es la mas aconsejable.
