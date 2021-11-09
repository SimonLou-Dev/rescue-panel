import React from 'react';
import axios from "axios";
export const rootUrl = document.querySelector('body').getAttribute('data-root-url');

class ContentCard extends React.Component {
    constructor(props) {
        super(props);
        this.state = {title: "", path: "", price: 0,items: [], formcontent: '', type: this.props.type, data:false};
        this.post = this.post.bind(this);
        this.delete = this.delete.bind(this);
        this.display = this.display.bind(this);
    }
    async componentDidMount() {
        switch (this.props.type) {
            case 1:
                this.setState({title: "Types d'intervention"});
                break;
            case 2:
                this.setState({title: "Liste des hôpitaux"});
                break;
            case 3:
                this.setState({title: "Types de plan d'urgence"});
                break;
            case 4:
                this.setState({title: "Types de blessures"});
                break;
            case 5:
                this.setState({title: "Liste des annonces"});
                break;
            case 6:
                this.setState({title: "Vetements BC"});
                break;
            case 7:
                this.setState({title: 'Lieux survols'})
                break;
            case 8:
                this.setState({title: 'item remboursement'})
                break;
            case 9:
                this.setState({title: 'états de service'})
                break;
            case 10:
                this.setState({title: 'Primes'})
                break;
            default:
                break;
        }
        var req = await axios({
            method: 'GET',
            url: '/data/gestion/content/get/' + this.props.type,
        });
        this.setState({items: req.data.data,data:true});
    }

    async post(e) {
        e.preventDefault();
        if (this.state.formcontent !== "") {
            if(this.props.type === 8 || this.props.type === 10){
                var req = await axios({
                    url: '/data/gestion/content/add/' + this.state.type,
                    method: 'POST',
                    data: {
                        formcontent: this.state.formcontent,
                        price: this.state.price
                    }
                });
            }else if(this.props.type === 9){
                var req = await axios({
                    url: '/data/gestion/content/add/' + this.state.type,
                    method: 'POST',
                    data: {
                        name: this.state.formcontent,
                        color: this.state.color
                    }
                });
            }else{
                var req = await axios({
                    url: '/data/gestion/content/add/' + this.state.type,
                    method: 'POST',
                    data: {
                        formcontent: this.state.formcontent,
                    }
                });
            }
            if(req.status === 201){
                this.setState({formcontent:''})
                if(this.props.type === 8 || this.props.type === 10){
                    this.setState({price :0})
                }
                if(this.props.type === 9 ){
                    this.setState({color: ''})
                }
                this.componentDidMount();
            }
        }
    }

    async delete (e){
        var infos = e.target.getAttribute('data').split('_');
        var req = await axios({
            method: 'delete',
            url: '/data/gestion/content/delete/'+ infos[0] +'/' + infos[1]
        });
        if(req.status === 204){
            this.componentDidMount();
        }
    }

    async hide (e){
        var infos = e.target.getAttribute('data').split('_');
        var req = await axios({
            method: 'delete',
            url: '/data/gestion/content/hide/'+ infos[0] +'/' + infos[1]
        });
        if(req.status === 204){
            this.componentDidMount();
        }
    }

    display(id){
        if(this.props.type === 1){
            if(id === 1){
                return 'none';
            }
        }
        if(this.props.type === 2){
            if(id === 1){
                return 'none';
            }
        }
        return 'block';
    }



    render() {
        return (
            <div className={'ContentCard'}>
                <h1>{this.state.title}</h1>
                <div className={"item-list"}>
                    {this.state.data &&
                        this.state.items.map((item)=>
                            <div className={'item'} key={item.id}>
                                {this.props.type === 8  &&
                                    <p>{item.name} ${item.price}</p>
                                }
                                {this.props.type === 10  &&
                                <p>{item.name} ${item.montant}</p>
                                }
                                {this.props.type !== 8 && this.props.type !== 10 && item.name &&
                                        <p>{item.name}</p>
                                }
                                {this.props.type === 9 &&
                                    item.color &&
                                        <div className={'colorTag'} style={{backgroundColor:  item.color}}/>
                                }
                                {item.title&&
                                <p>{item.title}</p>
                                }
                                {this.props.type === 4 &&
                                    <button  style={{display: this.display(item.id)}} onClick={this.delete}><img alt={""} data={this.state.type + '_' + item.id} src={rootUrl + (item.deleted_at !== null ? 'assets/images/invisibility.svg' : 'assets/images/visibility.svg')}/></button>
                                }
                                {this.props.type !== 4 && !(this.props.type === 10 && item.id === 1) &&
                                <button  style={{display: this.display(item.id)}} onClick={this.delete}><img alt={""} data={this.state.type + '_' + item.id} src={rootUrl + 'assets/images/cancel.png'}/></button>
                                }
                            </div>
                        )
                    }
                    {!this.state.data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                    }

                </div>
                {this.state.type !== 5&&
                <form method={"POST"} onSubmit={this.post}>
                    <input type={"text"} value={this.state.formcontent} maxLength={"30"} onChange={(e)=>{this.setState({formcontent: e.target.value})}}/>
                    {this.props.type === 8 || this.props.type === 10 &&
                        <input type={'number'} value={this.state.price} onChange={(e)=>{this.setState({price:e.target.value})}} />
                    }
                    {this.props.type === 9 &&
                        <input type={'text'} value={this.state.color} placeholder={'#ffffff'} onChange={(e)=>{this.setState({color:e.target.value})}} />
                    }
                    <button type={'submit'} className={'btn'}>Ajouter</button>
                </form>}
            </div>
        )
    }
}

export default ContentCard;
