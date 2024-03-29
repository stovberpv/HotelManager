/*jshint esversion: 6 */
/*jshint -W030 */

'use strict';

class DML {
    constructor(query, schema, queryData) {
        this.query = query;
        this.schema = schema;
        this.queryData = queryData;
    }
    build() {
        if (this.query) return this.query;
        for (let op in this.schema) { (this.schema[op] !== '') && (this.query += ` ${op} ${this.schema[op]} `); }
        this.query = this.query.replace(/\s{2,}/g, ' ').trim();
        return this.query;
    }
    connect(auth = 0) {
        let self = this;

        return new Promise((resolve, reject) => {

            let types = self.queryData.types ? '&types=' + self.queryData.types : '',
                param = self.queryData.param ? '&param=' + JSON.stringify(self.queryData.param) : '',
                body = `sql=${self.build()}${types}${param}&auth=${auth}`;

            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../php/db.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send(body);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        let result = '';
                        try {
                            result = JSON.parse(xhr.responseText);
                        } catch (error) {
                            new MessageBox({ text: `${GL.CONST.LOG.ID.A001.TITLE}::${xhr.responseText}`, level: 'error' }).add().stay();
                        }
                        resolve(result);
                    } else {
                        new MessageBox({ text: `${GL.CONST.LOG.ID.B002.TITLE}::${GL.CONST.LOG.ID.B002.GIST}`, level: 'error' }).add().stay();
                        reject(xhr.status);
                    }
                } else {
                    // new MessageBox({ text: `${GL.CONST.LOG.ID.B000.TITLE}::${GL.CONST.LOG.ID.B000.GIST}`, level: 'info' }).add().stay(1000);
                }
            };
        });
    }
}

class Select extends DML {
    /**
     * Creates an instance of Select.
     * @param {string} [query=''] Prepared query string
     * @param {Object} [data={}] Object with keys: types: 'ss', param: ['param1', 'param2'] using for prepare statment
     * @memberof Select
     */
    constructor(query = '', data = {}) { super(query, { SELECT: '', FROM: '', JOIN: '', ON: '', WHERE: '' }, data); }
    select(select) { this.schema.SELECT = select; return this; }
    from(from)     { this.schema.FROM   = from;   return this; }
    where(where)   { this.schema.WHERE  = where;  return this; }
    join(join)     { this.schema.JOIN   = join;   return this; }
    on(on)         { this.schema.ON     = on;     return this; }
    build()        { return super.build(); }
    /**
     * 
     *
     * @param {number} [auth={0,1}] Flag - using current user name
     * @returns new Promise
     * @memberof Select
     */
    connect(auth = 0) { return super.connect(auth); }
}
class Insert extends DML {
    constructor(query = '', data = {}) { super(query, { INTO: '', VALUES: '', }, data); }
    into(into)     { this.schema.INTO   = into;   return this; }
    values(values) { this.schema.VALUES = values; return this; }
    build()        { return `INSERT ${super.build()}`; }
    connect(auth = 0) { return super.connect(auth); }
}
class Update extends DML{
    constructor(query = '', data = {}) { super(query, { UPDATE: '', JOIN: '', ON: '', SET: '', WHERE: '' }, data); }
    update(update) { this.schema.UPDATE = update; return this; }
    join(join)     { this.schema.JOIN   = join;   return this; }
    on(on)         { this.schema.ON     = on;     return this; }
    set(set)       { this.schema.SET    = set;    return this; }
    where(where)   { this.schema.WHERE  = where;  return this; }
    build()        { return super.build(); }
    connect(auth = 0) { return super.connect(auth); }
}
class Delete extends DML {
    constructor(query = '', data = {}) { super(query, { from: '', where: '' }, data); }
    from(from)   { this.schema.FROM   = from;  return this; }
    where(where) { this.schema.WHERE  = where; return this; }
    build()      { return `DELETE ${super.build()}`; }
    connect(auth = 0) { return super.connect(auth); }
}

// UTILS.DEEPF_REEZE(DB);