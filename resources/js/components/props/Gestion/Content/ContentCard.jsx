import React from 'react';
import axios from "axios";
export const rootUrl = document.querySelector('body').getAttribute('data-root-url');

class ContentCard extends React.Component {
    constructor(props) {
        super(props);
        this.state = {title: "", path: "", items: [], formcontent: '', type: this.props.type, data:false};
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
            var req = await axios({
                url: '/data/gestion/content/add/' + this.state.type,
                method: 'POST',
                data: {
                    formcontent: this.state.formcontent,
                }
            });
            if(req.status === 201){
                this.setState({formcontent:''})
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
                                {item.name &&
                                <p>{item.name}</p>
                                }
                                {item.title&&
                                <p>{item.title}</p>
                                }
                                <button  style={{display: this.display(item.id)}} onClick={this.delete}><img alt={""} data={this.state.type + '_' + item.id} src={rootUrl + 'assets/images/cancel.png'}/></button>
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
                    <button type={'submit'} className={'btn'}>Ajouter</button>
                </form>}
            </div>
        )
    }
}

export default ContentCard;
