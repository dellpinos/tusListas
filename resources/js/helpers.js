
/* Convierte en mayuscula la primer letra del string */
export function firstCap(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/* Redondea hacia arriba y a un multiplo de 10 */
export function redondear(numero) {
    return Math.ceil(numero / 10) * 10;
}

/* Formatear número como dinero */
export const formatearDinero = cantidad => {
    return cantidad.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD'
    })
}

export const formatearDineroSinDecimales = cantidad => {

    let resultado = Number(cantidad);

    resultado = resultado.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0,
        useGrouping: true
    })
    // utiliza punto para reemplazar la coma
    return resultado.replace(',', '.');
}