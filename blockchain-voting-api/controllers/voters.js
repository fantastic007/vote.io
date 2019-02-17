const axios = require('axios');

const blockchainAuthToken = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1NTAzMjMxMDcsInVzZXJuYW1lIjoic2h1aGFuIiwib3JnTmFtZSI6Ik9yZzEiLCJpYXQiOjE1NTAyODcxMDd9.-es8VySmyKgjleM5t-aOY1i62IDm3_2eNccBtUZDY4M';
const endpoint = 'http://103.84.159.230:6000/channels/mychannel/chaincodes/mycc';

const parseNominations = (data) => {
    let list = {};
    data.forEach((item) => {
        list = {
            ...list,
            [item.Record.position]: [
                ...list[item.Record.position] || [],
                item.Record.idto,
            ],
        }
    });

    // remove duplicates
    for (let key in list) {
        list[key] = Array.from(new Set(list[key]));
    }
    return list;
}

const parseResults = (data) => {
    let list = {};
    console.log('mydata', data);
    data.forEach((item, index) => {
        if(!list[item.Record.position]) {
            list[item.Record.position] = [];
        }
        const dataIndex = list[item.Record.position].findIndex((i) => {
            console.log(i.id, item.Record.idto);
            return i.id === item.Record.idto;
        });

        if (dataIndex === -1) {
            list[item.Record.position].push({ id: item.Record.idto, count: 1});
        } else {
            list[item.Record.position][dataIndex].count += 1;
        }
    });
    return list;
};

exports.getAllVoters = async (req, res, next) => {
    const params = {
        peer: 'peer0.org1.example.com',
        fcn: 'queryAllUsers',
        args: `[""]`
    };

    const headers = {
        'Content-Type': 'application/x-www-form-urlencoded',
        authorization: blockchainAuthToken
    };

    const config = {
        headers,
        params
    };

    try {
        const response = await axios.get(endpoint, config);
        const data = JSON.parse(response.data.split('=>')[1]);
        console.log(data);
        res.send({
            data
        });
    } catch (e) {
        res.status(404).send({
            message: 'Error occured'
        })
    }   
}

const nominationHelper = async (type) => {
    const params = {
        peer: 'peer0.org1.example.com',
        fcn: 'getNominationList',
        args: `["${type}"]`
    };

    const headers = {
        'Content-Type': 'application/x-www-form-urlencoded',
        authorization: blockchainAuthToken
    };

    const config = {
        headers,
        params
    };

    try {
        const response = await axios.get(endpoint, config);
        console.log(response.data);
        const data = JSON.parse(response.data.split('=>')[1]);

        if (type === 0) {
            // get nomination list
            const list = parseNominations(data);
            return list;
        } else if (type === 1) {
            // get vote cast list
            const list = parseResults(data);
            return list;
        } else if (type === 2) {
            // get positions
            return data;
        } else {
            return false;
        }
        
    } catch (e) {
        console.log(e);
        return false;
    }  
};

// get nomination list or vote cast list or positions
exports.getNominations = async (req, res, next) => {
    const { type } = req.query;
    console.log('type', type);
    const data = await nominationHelper(parseInt(type));
    if (!data) return res.status(404).send({
        reply: false,
        message: 'Invalid type supplied'
    });
    res.send({
        data
    });
};

exports.getUser = async (req, res, next) => {
    const { nid } = req.query;
    const params = {
        peer: 'peer0.org1.example.com',
        fcn: 'queryUser',
        args: `["USER${nid}"]`
    };

    const headers = {
        'Content-Type': 'application/x-www-form-urlencoded',
        authorization: blockchainAuthToken
    }

    const config = {
        headers,
        params
    }

    try {
        const response = await axios.get(endpoint, config);
        const data = JSON.parse(response.data.split('=>')[1]);
        console.log(data);
        
        return res.send({
            id: data.id,
            username: data.name,
            message: 'Success',
        });
        
    } catch (e) {
        console.log('error in login', e);
        return res.status(404).send({
            reply: false,
            message: 'User not found'
        });
    }
};

// handles both nomination and vote casting
exports.nominate = async (req, res, next) => {
    const { from , to , pos, type } = req.body;
    console.log(from, to , pos, type);

    if (!from || !to || !pos || !type) {
        console.log('error in params');
        return res.status(404).send({
            reply: false,
            error: 'Parameter error'
        });
    }

    // ignore the nominations marked as NA
    if (parseInt(type) === 0 && to === 'NA') {
        console.log('nomination ignored');
        return res.send({
            reply: true
        });
    }

    const bodyData = {
        peers: ['peer0.org1.example.com', 'peer0.org2.example.com'],
        fcn: 'nominate',
        args: [from, to, pos, type] // 0 -> nominate
    };

    const headers = {
        'Content-Type': 'application/json',
        authorization: blockchainAuthToken
    };

    try {
        const response = await axios.post(endpoint, bodyData, { headers });
        console.log(response.data);
        if (response.data) {
            res.send({
                message: 'Success',
                id: response.data,
                reply: true
            });
        } else {
            res.status(404).send({
                reply: false,
                message: 'Invalid vote/nomination cast'
            });
        }
        // res.send({
        //     message: 'Success',
        //     id: response.data,
        //     reply: true
        // });
        
    } catch (e) {
        console.log(e);
        res.status(404).send({
            reply: false,
            message: 'Error occured'
        });
    }
};

// get all the candidates the user has nominated
exports.getUserNominations = async (req, res, next) => {
    const { nid } = req.query;
    const params = {
        peer: 'peer0.org1.example.com',
        fcn: 'getNominationList',
        args: `["0"]`
    };

    const headers = {
        'Content-Type': 'application/x-www-form-urlencoded',
        authorization: blockchainAuthToken
    };

    const config = {
        headers,
        params
    };

    try {
        const response = await axios.get(endpoint, config);
        const data = JSON.parse(response.data.split('=>')[1]);
        const list = data.filter(item => item.Record.idfrom === nid);
        
        return res.send({
            reply: true,
            data: list
        });
    } catch (e) {
        return res.status(404).send({
            reply: false,
            message: 'Invalid NID'
        })
    }
};