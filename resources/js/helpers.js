



/* Convierte en mayuscula la primer letra del string */
export function firstCap(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/* Redondea hacia arriba y a un multiplo de 10 */
export function redondear(numero) {
    return Math.ceil(numero / 10) * 10;
}