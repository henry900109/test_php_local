import json
def A():
    data = {}
    data["name"] = "henry"
    data = json.dumps(data)
    return data
print(A())