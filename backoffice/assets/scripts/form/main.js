Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

function valida_cpf(s)
{
    var s = s.replace('.', '').replace('.', '').replace('-', '');

    if(s.length<11)
    {
        return false;
    }
    else
    {
        if(s=="11111111111" || s=="22222222222" || s=="33333333333" || s=="44444444444" || s=="55555555555" || s=="66666666666" || s=="77777777777" || s=="88888888888" || s=="99999999999")
        { 
            return false;
        }
        else
        {
            var v = 0;
            var c = s.substr(0,9);
            var dv = s.substr(9,2);
            var d1 = 0;
            for(i=0; i<9; i++)
            {
                var num_c = c.substr(i, 1);			
                d1 += num_c * (10-i);
            }
            if(d1==0)
            {
                v=1;
                return false;
            }
            else
            {
                d1 = 11 - (d1%11);
                if (d1>9) d1 = 0;
                var num_dv = dv.substr(0, 1);
                if (num_dv != d1)
                {
                    v=v+1;
                    return false;
                }
                else
                {	
                    d1 *= 2;
                    for(i=0; i<9; i++)
                    {
                        var num_c = c.substr(i, 1);
                        d1 += num_c * (11-i);
                    }
                    d1 = 11-(d1%11);
                    if(d1>9) d1 = 0;
                    var num_dv = dv.substr(1, 1);
                    if(num_dv != d1)
                    {
                        v=v+1;
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
            }
        }
    }
}